<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\HomologacionAsignatura;
use App\Models\Asignatura;
use App\Models\Programa;
use App\Models\Facultad;
use App\Models\Institucion;
use App\Models\SolicitudAsignatura;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\ContenidoProgramatico;

class HomologacionAsignaturaControllerApi extends Controller
{
    /**
     * Método para obtener todas las homologaciones de asignaturas.
     * Utiliza un procedimiento almacenado y formatea los resultados.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function traerHomologacionAsignaturas()
    {
        try {
            // Llamada al procedimiento almacenado
            $homologaciones = DB::select('CALL ObtenerHomologacionesAsignaturas()');
            $resultados = [];

            // Formatear cada resultado para darle la estructura adecuada
            foreach ($homologaciones as $homologacion) {
                $resultados[] = $this->formatearDatosHomologacion($homologacion);
            }

            return response()->json($resultados);
        } catch (\Exception $e) {
            // Manejo detallado de errores incluyendo línea y archivo
            return response()->json([
                'mensaje' => 'Error al obtener las homologaciones de asignaturas',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Método para obtener una homologación de asignatura específica por su ID.
     *
     * @param int $id ID de la homologación a buscar
     * @return \Illuminate\Http\JsonResponse
     */
    public function llevarHomologacionAsignatura($id)
    {
        try {
            // Llamada al procedimiento almacenado con el ID como parámetro
            $homologacion = DB::select('CALL ObtenerHomologacionAsignaturaPorId(?)', [$id]);

            if (!empty($homologacion)) {
                // Convertir el resultado a formato estandarizado
                $datosFormateados = $this->formatearDatosHomologacion($homologacion[0]);

                return response()->json([
                    'mensaje' => 'Homologación de asignatura encontrada',
                    'datos' => $datosFormateados
                ], 200);
            } else {
                // Respuesta cuando no se encuentra la homologación
                return response()->json([
                    'mensaje' => 'Homologación de asignatura no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo detallado de errores
            return response()->json([
                'mensaje' => 'Error al obtener la homologación de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Método para insertar una nueva homologación de asignatura.
     * Recibe las asignaturas de origen y crea homologaciones pendientes.
     *
     * @param Request $request Datos de la solicitud
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertarHomologacionAsignatura(Request $request)
    {
        try {
            // Validación de datos de entrada
            $request->validate([
                'solicitud_id' => 'required|integer',
                'asignaturas_origen' => 'required|array',
                'asignaturas_origen.*' => 'required|integer',
                'ruta_pdf_resolucion' => 'nullable|file|mimes:pdf|max:10240', // Máximo 10 MB
            ]);

            // Obtenemos las asignaturas de origen del request
            $asignaturasOrigen = $request->asignaturas_origen;

            // Creamos la estructura inicial de homologaciones sin asignaturas de destino asignadas
            $homologaciones = [];
            foreach ($asignaturasOrigen as $asignaturaOrigenId) {
                $homologaciones[] = [
                    'asignatura_origen_id' => $asignaturaOrigenId,
                    'asignatura_destino_id' => null, // Pendiente de asignar
                    'nota_destino' => null,          // Pendiente de calificar
                    'comentarios' => null            // Sin comentarios iniciales
                ];
            }

            // Convertimos el array de homologaciones a formato JSON para almacenar
            $homologacionesJson = json_encode($homologaciones);

            // Manejar la carga del archivo PDF si está presente
            $pdfPath = null;
            if ($request->hasFile('ruta_pdf_resolucion')) {
                // Guardar el archivo PDF en el directorio 'resoluciones_homologaciones'
                $pdfPath = $request->file('ruta_pdf_resolucion')->store('resoluciones_homologaciones', 'public');
            }

            // Insertar mediante procedimiento almacenado
            DB::statement('CALL InsertarHomologacionAsignatura(?, ?, ?, ?)', [
                $request->solicitud_id,
                $homologacionesJson,
                Carbon::now()->toDateString(), // Fecha actual
                $pdfPath // Ruta del PDF guardado
            ]);

            // Respuesta exitosa con los datos creados
            return response()->json([
                'mensaje' => 'Homologación de asignatura insertada correctamente',
                'datos' => [
                    'solicitud_id' => $request->solicitud_id,
                    'homologaciones' => $homologaciones,
                    'ruta_pdf_resolucion' => $pdfPath
                ]
            ], 201);
        } catch (\Exception $e) {
            // Manejo detallado de errores
            return response()->json([
                'mensaje' => 'Error al insertar la homologación de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Método para actualizar una homologación de asignatura existente.
     * Permite asignar asignaturas destino, notas, comentarios y PDF de resolución.
     *
     * @param Request $request Datos de la actualización
     * @param int $id ID de la homologación a actualizar
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarHomologacionAsignatura(Request $request, $id)
    {
        try {
            // Validación de datos de entrada
            $request->validate([
                'homologaciones' => 'required|array',
                'homologaciones.*.asignatura_origen_id' => 'required|integer',
                'homologaciones.*.asignatura_destino_id' => 'nullable|integer',
                'homologaciones.*.nota_destino' => 'nullable|numeric',
                'homologaciones.*.comentarios' => 'nullable|string',
                'ruta_pdf_resolucion' => 'nullable|file|mimes:pdf|max:10240', // Máximo 10 MB
            ]);

            // Obtener la homologación actual de la base de datos
            $homologacion = HomologacionAsignatura::findOrFail($id);

            // Decodificar las homologaciones existentes (de JSON a array)
            $homologacionesActuales = $homologacion->homologaciones;

            // Crear un mapeo para buscar rápidamente por ID de asignatura origen
            $mapeoIndices = [];
            foreach ($homologacionesActuales as $key => $homologacionItem) {
                $mapeoIndices[$homologacionItem['asignatura_origen_id']] = $key;
            }

            // Arrays para seguimiento de cambios
            $actualizadas = [];
            $noEncontradas = [];

            // Procesar cada homologación del request
            foreach ($request->homologaciones as $homologacionRequest) {
                $origenId = $homologacionRequest['asignatura_origen_id'];

                // Verificar si la asignatura origen existe en la homologación actual
                if (isset($mapeoIndices[$origenId])) {
                    $index = $mapeoIndices[$origenId];

                    // Actualizar solo los campos relacionados con el destino
                    $homologacionesActuales[$index]['asignatura_destino_id'] = $homologacionRequest['asignatura_destino_id'];
                    $homologacionesActuales[$index]['nota_destino'] = $homologacionRequest['nota_destino'];
                    $homologacionesActuales[$index]['comentarios'] = $homologacionRequest['comentarios'] ?? null;

                    $actualizadas[] = $origenId;
                } else {
                    // Registrar las asignaturas que no se encontraron
                    $noEncontradas[] = $origenId;
                }
            }

            // Si hay asignaturas no encontradas, devolvemos advertencia
            if (!empty($noEncontradas)) {
                return response()->json([
                    'mensaje' => 'Algunas asignaturas de origen no fueron encontradas en esta homologación',
                    'asignaturas_no_encontradas' => $noEncontradas,
                    'asignaturas_actualizadas' => $actualizadas
                ], 400);
            }

            // Convertir el array actualizado a JSON para almacenar
            $homologacionesJson = json_encode($homologacionesActuales);

            // Manejar la actualización del archivo PDF si está presente
            $pdfPath = $homologacion->ruta_pdf_resolucion; // Mantener el valor actual por defecto
            if ($request->hasFile('ruta_pdf_resolucion')) {
                // Si ya existe un PDF anterior, eliminarlo del storage
                if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                    Storage::disk('public')->delete($pdfPath);
                }

                // Guardar el nuevo archivo PDF
                $pdfPath = $request->file('ruta_pdf_resolucion')->store('resoluciones_homologaciones', 'public');
            }

            // Actualizar mediante procedimiento almacenado
            DB::statement('CALL ActualizarHomologacionAsignatura(?, ?, ?, ?, ?)', [
                $id,
                $homologacion->solicitud_id,
                $homologacionesJson,
                now()->toDateString(),
                $pdfPath
            ]);

            // Respuesta exitosa con información de cambios
            return response()->json([
                'mensaje' => 'Homologación de asignaturas actualizada correctamente',
                'asignaturas_actualizadas' => $actualizadas,
                'total_actualizadas' => count($actualizadas),
                'ruta_pdf_resolucion' => $pdfPath
            ], 200);
        } catch (\Exception $e) {
            // Manejo detallado de errores
            return response()->json([
                'mensaje' => 'Error al actualizar la homologación de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Método para eliminar una homologación de asignatura.
     *
     * @param int $id ID de la homologación a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarHomologacionAsignatura($id)
    {
        try {
            // Obtener la homologación primero para poder eliminar el archivo asociado
            $homologacion = HomologacionAsignatura::find($id);

            if ($homologacion && $homologacion->ruta_pdf_resolucion) {
                // Eliminar el archivo PDF del storage si existe
                if (Storage::disk('public')->exists($homologacion->ruta_pdf_resolucion)) {
                    Storage::disk('public')->delete($homologacion->ruta_pdf_resolucion);
                }
            }

            // Eliminar mediante procedimiento almacenado
            DB::statement('CALL EliminarHomologacionAsignatura(?)', [$id]);

            return response()->json([
                'mensaje' => 'Homologación de asignatura eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo detallado de errores
            return response()->json([
                'mensaje' => 'Error al eliminar la homologación de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Método privado para dar formato a los datos de homologación.
     * Estructura la información completa incluyendo datos relacionados.
     *
     * @param object $homologacion Datos de la homologación a formatear
     * @return array Datos formateados en la estructura requerida
     */
    private function formatearDatosHomologacion($homologacion)
    {
        try {
            // Obtener información relacionada de la solicitud
            $solicitud = Solicitud::find($homologacion->solicitud_id);

            // Información del estudiante
            $estudiante = User::find($solicitud->usuario_id ?? 0);

            // Obtener programa de destino del estudiante
            $programaDestino = Programa::find($solicitud->programa_destino_id ?? 0);

            // Estructura base de la respuesta
            $resultado = [
                'id_homologacion' => $homologacion->id_homologacion,
                'solicitud_id' => $homologacion->solicitud_id,
                'numero_radicado' => $homologacion->numero_radicado,
                'estudiante' => $homologacion->estudiante,
                'numero_identificacion' => $estudiante->numero_identificacion ?? 'No disponible',
                'programa_destino' => $programaDestino->nombre ?? 'No disponible',
                'estado_solicitud' => $solicitud->estado ?? 'No disponible',
                'fecha' => $homologacion->fecha,
                'ruta_pdf_resolucion' => $homologacion->ruta_pdf_resolucion, // Incluimos la ruta del PDF
                'asignaturas_origen' => [],
                'asignaturas_destino' => [],
                'comentarios' => ''  // Comentario general inicializado como string vacío
            ];

            // Si hay una ruta de PDF, construir la URL completa para acceso
            if ($resultado['ruta_pdf_resolucion']) {
                $resultado['url_pdf_resolucion'] = asset('storage/' . $resultado['ruta_pdf_resolucion']);
            } else {
                $resultado['url_pdf_resolucion'] = null;
            }

            // Manejo de diferentes formatos de datos para las homologaciones
            $homologacionesArray = null;
            if (is_string($homologacion->homologaciones)) {
                // Es una cadena JSON - decodificar
                $homologacionesArray = json_decode($homologacion->homologaciones, true);
            } elseif (is_array($homologacion->homologaciones)) {
                // Ya es un array
                $homologacionesArray = $homologacion->homologaciones;
            } elseif (is_object($homologacion->homologaciones)) {
                // Es un objeto - convertir a array
                $homologacionesArray = json_decode(json_encode($homologacion->homologaciones), true);
            } else {
                // Valor desconocido - usar array vacío
                $homologacionesArray = [];
            }

            // Validar que sea un array
            if (!is_array($homologacionesArray)) {
                return $resultado;
            }

            // Determinar si es una homologación SENA
            try {
                $solicitudAsignatura = SolicitudAsignatura::where('solicitud_id', $homologacion->solicitud_id)->first();
                $esSena = false;
                $asignaturasOrigen = [];

                if ($solicitud && $solicitudAsignatura) {
                    // Manejar diferentes formatos de datos para las asignaturas
                    if (is_string($solicitudAsignatura->asignaturas)) {
                        $asignaturasOrigen = json_decode($solicitudAsignatura->asignaturas, true);
                    } elseif (is_array($solicitudAsignatura->asignaturas)) {
                        $asignaturasOrigen = $solicitudAsignatura->asignaturas;
                    } elseif (is_object($solicitudAsignatura->asignaturas)) {
                        $asignaturasOrigen = json_decode(json_encode($solicitudAsignatura->asignaturas), true);
                    }

                    // Determinar si es SENA basado en la presencia de horas_sena
                    if (is_array($asignaturasOrigen) && count($asignaturasOrigen) > 0) {
                        foreach ($asignaturasOrigen as $asignatura) {
                            if (isset($asignatura['horas_sena']) && $asignatura['horas_sena'] !== null) {
                                $esSena = true;
                                break;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // En caso de error, valores por defecto
                $esSena = false;
                $asignaturasOrigen = [];
            }

            // Recolector de comentarios
            $comentariosRecolectados = [];

            // Procesar cada par de homologación (origen-destino)
            foreach ($homologacionesArray as $homologacionItem) {
                // Obtener IDs de las asignaturas
                $asignaturaOrigenId = $homologacionItem['asignatura_origen_id'] ?? 0;
                $asignaturaDestinoId = $homologacionItem['asignatura_destino_id'] ?? null;

                // Obtener información detallada de la asignatura origen
                $asignaturaOrigen = $this->obtenerInfoAsignatura($asignaturaOrigenId);

                // Añadir contenido programático de la asignatura origen
                $asignaturaOrigen['contenido_programatico'] = $this->obtenerContenidoProgramatico($asignaturaOrigenId);

                // Establecer universidad_origen en el resultado principal si aún no se ha establecido
                if (empty($resultado['universidad_origen']) && !empty($asignaturaOrigen['institucion']) && $asignaturaOrigen['institucion'] != 'No disponible') {
                    $resultado['universidad_origen'] = $asignaturaOrigen['institucion'];
                }

                // Buscar información adicional en la solicitud original (notas o horas SENA)
                if (isset($asignaturasOrigen) && is_array($asignaturasOrigen)) {
                    foreach ($asignaturasOrigen as $asignaturaSol) {
                        if (isset($asignaturaSol['asignatura_id']) && $asignaturaSol['asignatura_id'] == $asignaturaOrigenId) {
                            // Modificar esta parte para incluir siempre la nota_origen en ambos casos
                            if ($esSena && isset($asignaturaSol['horas_sena'])) {
                                $asignaturaOrigen['horas_sena'] = $asignaturaSol['horas_sena'];
                            }

                            // Siempre incluir la nota_origen si está disponible, independientemente de si es SENA o no
                            if (isset($asignaturaSol['nota_origen'])) {
                                $asignaturaOrigen['nota_origen'] = $asignaturaSol['nota_origen'];
                            }

                            // Añadir créditos si están disponibles
                            if (isset($asignaturaSol['creditos'])) {
                                $asignaturaOrigen['creditos'] = $asignaturaSol['creditos'];
                            }
                            break;
                        }
                    }
                }

                // Eliminar campos innecesarios según el tipo de homologación
                if (!$esSena) {
                    unset($asignaturaOrigen['horas_sena']);
                }

                // Añadir asignatura origen al resultado
                $resultado['asignaturas_origen'][] = $asignaturaOrigen;

                // Procesar asignatura destino
                $asignaturaDestino = null;
                if ($asignaturaDestinoId) {
                    // Si hay asignatura destino asignada, obtener su información
                    $asignaturaDestino = $this->obtenerInfoAsignatura($asignaturaDestinoId);
                    // Añadir nota de destino desde la homologación
                    $asignaturaDestino['nota_destino'] = $homologacionItem['nota_destino'] ?? null;
                    // Eliminar nota_origen que no aplica en destino
                    unset($asignaturaDestino['nota_origen']);
                    // Obtener contenido programático
                    $asignaturaDestino['contenido_programatico'] = $this->obtenerContenidoProgramatico($asignaturaDestinoId);

                    // Eliminar campos innecesarios según el tipo
                    if (!$esSena) {
                        unset($asignaturaDestino['horas_sena']);
                    }
                } else {
                    // Si no hay asignatura destino, crear estructura vacía
                    $asignaturaDestino = [
                        'id' => null,
                        'nombre' => null,
                        'codigo' => null,
                        'semestre' => null,
                        'programa' => null,
                        'facultad' => null,
                        'institucion' => null,
                        'nota_destino' => null,
                        'creditos' => null,
                        'contenido_programatico' => null
                    ];

                    // Añadir horas_sena solo si es SENA
                    if ($esSena) {
                        $asignaturaDestino['horas_sena'] = null;
                    }
                }

                // Añadir asignatura destino al resultado
                $resultado['asignaturas_destino'][] = $asignaturaDestino;

                // Recolectar comentarios individuales
                if (isset($homologacionItem['comentarios']) && !empty($homologacionItem['comentarios'])) {
                    $nombreAsignaturaOrigen = $asignaturaOrigen['nombre'] ?? 'Asignatura origen';
                    $nombreAsignaturaDestino = $asignaturaDestino['nombre'] ?? 'Asignatura destino';

                    $comentarioFormateado = "{$nombreAsignaturaOrigen} → {$nombreAsignaturaDestino}: {$homologacionItem['comentarios']}";
                    $comentariosRecolectados[] = $comentarioFormateado;
                }
            }

            // Unir todos los comentarios en un solo string
            if (!empty($comentariosRecolectados)) {
                $resultado['comentarios'] = implode("\n", $comentariosRecolectados);
            }

            return $resultado;
        } catch (\Exception $e) {
            // En caso de error, devolver estructura mínima con información del error
            return [
                'id_homologacion' => $homologacion->id_homologacion ?? 0,
                'solicitud_id' => $homologacion->solicitud_id ?? 0,
                'numero_radicado' => $homologacion->numero_radicado ?? 'No disponible',
                'estudiante' => $homologacion->estudiante ?? 'No disponible',
                'fecha' => $homologacion->fecha ?? null,
                'ruta_pdf_resolucion' => $homologacion->ruta_pdf_resolucion ?? null,
                'url_pdf_resolucion' => $homologacion->ruta_pdf_resolucion ? asset('storage/' . $homologacion->ruta_pdf_resolucion) : null,
                'error' => 'Error al formatear datos: ' . $e->getMessage(),
                'asignaturas_origen' => [],
                'asignaturas_destino' => [],
                'comentarios' => ''
            ];
        }
    }

    /**
     * Método privado auxiliar para obtener información detallada de una asignatura.
     * Incluye datos de programa, facultad e institución relacionados.
     *
     * @param int $asignaturaId ID de la asignatura
     * @return array Información detallada de la asignatura
     */
    private function obtenerInfoAsignatura($asignaturaId)
    {
        // Estructura base de la información
        $info = [
            'id' => $asignaturaId,
            'nombre' => 'No disponible',
            'codigo' => 'N/A',
            'semestre' => 0,
            'programa' => 'No disponible',
            'facultad' => 'No disponible',
            'institucion' => 'No disponible',
            'nota_origen' => null,
            'creditos' => null,
            'horas_sena' => null
        ];

        // Si no hay ID, devolver estructura vacía
        if (empty($asignaturaId)) {
            return $info;
        }

        try {
            // Buscar la asignatura por ID
            $asignatura = Asignatura::find($asignaturaId);

            if (!$asignatura) {
                return $info;
            }

            // Llenar información básica de la asignatura
            $info['nombre'] = $asignatura->nombre ?? 'No disponible';
            $info['codigo'] = $asignatura->codigo_asignatura ?? 'N/A';
            $info['semestre'] = $asignatura->semestre ?? 0;
            $info['creditos'] = $asignatura->creditos ?? null;

            // Obtener información relacionada del programa y sus dependencias
            try {
                if ($asignatura->programa_id) {
                    $programa = Programa::find($asignatura->programa_id);
                    if ($programa) {
                        $info['programa'] = $programa->nombre ?? 'No disponible';

                        // Obtener facultad relacionada
                        if ($programa->facultad_id) {
                            $facultad = Facultad::find($programa->facultad_id);
                            if ($facultad) {
                                $info['facultad'] = $facultad->nombre ?? 'No disponible';
                            }
                        }

                        // Obtener institución relacionada
                        if ($programa->institucion_id) {
                            $institucion = Institucion::find($programa->institucion_id);
                            if ($institucion) {
                                $info['institucion'] = $institucion->nombre ?? 'No disponible';
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Silenciar errores en relaciones
            }
        } catch (\Exception $e) {
            // Silenciar errores generales
        }

        return $info;
    }

    /**
     * Método privado para obtener el contenido programático de una asignatura.
     *
     * @param int $asignaturaId ID de la asignatura
     * @return array|null Contenido programático o null si no existe
     */
    private function obtenerContenidoProgramatico($asignaturaId)
    {
        if (empty($asignaturaId)) {
            return null;
        }

        try {
            // Buscar el contenido programático asociado a la asignatura
            $contenidoProgramatico = ContenidoProgramatico::where('asignatura_id', $asignaturaId)->first();

            if (!$contenidoProgramatico) {
                return null;
            }

            // Devolver estructura con campos relevantes
            return [
                'id' => $contenidoProgramatico->id_contenido ?? null,
                'tema' => $contenidoProgramatico->tema ?? null,
                'resultados_aprendizaje' => $contenidoProgramatico->resultados_aprendizaje ?? null,
                'descripcion' => $contenidoProgramatico->descripcion ?? null
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
    /**
     * Método para obtener todas las asignaturas de la Universidad Autónoma.
     * Útil para seleccionar asignaturas destino en homologaciones.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerAsignaturasAutonoma()
    {
        try {
            // Buscar la institución por nombre (con variantes en la escritura)
            $institucion = Institucion::where('nombre', 'like', '%autónoma%')
                ->orWhere('nombre', 'like', '%autonoma%')
                ->first();

            if (!$institucion) {
                return response()->json([
                    'mensaje' => 'No se encontró la institución Autónoma',
                    'asignaturas' => []
                ], 404);
            }

            // Obtener los IDs de programas asociados a la institución
            $programasIds = Programa::where('institucion_id', $institucion->id)->pluck('id')->toArray();

            if (empty($programasIds)) {
                return response()->json([
                    'mensaje' => 'No se encontraron programas para la institución Autónoma',
                    'asignaturas' => []
                ], 404);
            }

            // Obtener asignaturas de esos programas con relaciones
            $asignaturas = Asignatura::whereIn('programa_id', $programasIds)
                ->with(['programa', 'programa.facultad', 'programa.institucion'])
                ->get();

            // Formatear las asignaturas para la respuesta
            $asignaturasFormateadas = [];
            foreach ($asignaturas as $asignatura) {
                $asignaturaFormateada = [
                    'id' => $asignatura->id,
                    'nombre' => $asignatura->nombre,
                    'codigo' => $asignatura->codigo_asignatura,
                    'semestre' => $asignatura->semestre,
                    'creditos' => $asignatura->creditos,
                    'programa' => $asignatura->programa->nombre ?? 'No disponible',
                    'facultad' => $asignatura->programa->facultad->nombre ?? 'No disponible',
                    'institucion' => $asignatura->programa->institucion->nombre ?? 'No disponible',
                    'contenido_programatico' => $this->obtenerContenidoProgramatico($asignatura->id)
                ];

                $asignaturasFormateadas[] = $asignaturaFormateada;
            }

            return response()->json([
                'mensaje' => 'Asignaturas de la Universidad Autónoma obtenidas correctamente',
                'asignaturas' => $asignaturasFormateadas
            ], 200);
        } catch (\Exception $e) {
            // Manejo detallado de errores
            return response()->json([
                'mensaje' => 'Error al obtener las asignaturas de la Universidad Autónoma',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }
}
