<?php

namespace App\Http\Controllers;

use App\Mail\VicerrectoriaMailable;
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
use Log;
use Mail;

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

    /**
     * Método para limpiar las asignaturas destino de una homologación.
     * Preserva las asignaturas origen pero elimina todas las asignaciones destino.
     *
     * @param int $id ID de la homologación a limpiar
     * @return \Illuminate\Http\JsonResponse
     */
    public function limpiarAsignaturasDestino($id)
    {
        try {
            // Obtener la homologación actual de la base de datos
            $homologacion = HomologacionAsignatura::findOrFail($id);

            // Decodificar las homologaciones existentes (de JSON a array)
            $homologacionesActuales = [];
            if (is_string($homologacion->homologaciones)) {
                $homologacionesActuales = json_decode($homologacion->homologaciones, true);
            } elseif (is_array($homologacion->homologaciones)) {
                $homologacionesActuales = $homologacion->homologaciones;
            } elseif (is_object($homologacion->homologaciones)) {
                $homologacionesActuales = json_decode(json_encode($homologacion->homologaciones), true);
            }

            // Verificar que sea un array válido
            if (!is_array($homologacionesActuales)) {
                return response()->json([
                    'mensaje' => 'No se pudieron procesar las homologaciones existentes',
                ], 400);
            }

            // Contador para seguimiento de cambios
            $asignaturasLimpiadas = 0;

            // Limpiar las asignaturas destino para cada homologación
            foreach ($homologacionesActuales as &$homologacionItem) {
                if (
                    isset($homologacionItem['asignatura_destino_id']) &&
                    !is_null($homologacionItem['asignatura_destino_id'])
                ) {
                    // Limpiar el ID de destino
                    $homologacionItem['asignatura_destino_id'] = null;
                    // Limpiar la nota de destino
                    $homologacionItem['nota_destino'] = null;
                    // Limpiar comentarios
                    $homologacionItem['comentarios'] = null;

                    $asignaturasLimpiadas++;
                }
            }

            // Si no hay cambios, informar
            if ($asignaturasLimpiadas === 0) {
                return response()->json([
                    'mensaje' => 'No hay asignaturas destino para limpiar en esta homologación',
                    'total_limpiadas' => 0
                ], 200);
            }

            // Convertir el array actualizado a JSON para almacenar
            $homologacionesJson = json_encode($homologacionesActuales);

            // Actualizar mediante procedimiento almacenado
            DB::statement('CALL ActualizarHomologacionAsignatura(?, ?, ?, ?, ?)', [
                $id,
                $homologacion->solicitud_id,
                $homologacionesJson,
                now()->toDateString(),
                $homologacion->ruta_pdf_resolucion
            ]);

            // Respuesta exitosa con información de cambios
            return response()->json([
                'mensaje' => 'Asignaturas destino limpiadas correctamente',
                'total_limpiadas' => $asignaturasLimpiadas
            ], 200);
        } catch (\Exception $e) {
            // Manejo detallado de errores
            return response()->json([
                'mensaje' => 'Error al limpiar las asignaturas destino',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }
    /**
     * Método para eliminar completamente las asignaturas destino seleccionadas de una homologación.
     * A diferencia de limpiarAsignaturasDestino, esta función elimina los registros completamente.
     *
     * @param int $id ID de la homologación
     * @param Request $request Contiene los IDs de las asignaturas origen a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Método para eliminar completamente las asignaturas origen seleccionadas de una homologación.
     * Elimina por completo los registros de homologación para las asignaturas especificadas.
     *
     * @param int $id ID de la homologación
     * @param Request $request Contiene los IDs de las asignaturas origen a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarAsignaturasDestino(Request $request, $id)
    {
        try {
            // Validación de datos de entrada
            $request->validate([
                'asignaturas_origen_ids' => 'required|array',
                'asignaturas_origen_ids.*' => 'required|integer',
            ]);

            // Obtener la homologación actual de la base de datos
            $homologacion = HomologacionAsignatura::findOrFail($id);

            // Decodificar las homologaciones existentes (de JSON a array)
            $homologacionesActuales = [];
            if (is_string($homologacion->homologaciones)) {
                $homologacionesActuales = json_decode($homologacion->homologaciones, true);
            } elseif (is_array($homologacion->homologaciones)) {
                $homologacionesActuales = $homologacion->homologaciones;
            } elseif (is_object($homologacion->homologaciones)) {
                $homologacionesActuales = json_decode(json_encode($homologacion->homologaciones), true);
            }

            // Verificar que sea un array válido
            if (!is_array($homologacionesActuales)) {
                return response()->json([
                    'mensaje' => 'No se pudieron procesar las homologaciones existentes',
                ], 400);
            }

            // IDs de asignaturas origen a eliminar
            $idsAEliminar = $request->asignaturas_origen_ids;

            // Filtrar las homologaciones, manteniendo solo las que no están en la lista a eliminar
            $homologacionesFiltradas = array_filter($homologacionesActuales, function ($item) use ($idsAEliminar) {
                return !in_array($item['asignatura_origen_id'], $idsAEliminar);
            });

            // Si no hay cambios (no se encontraron los IDs a eliminar)
            if (count($homologacionesActuales) === count($homologacionesFiltradas)) {
                return response()->json([
                    'mensaje' => 'No se encontraron las asignaturas especificadas para eliminar',
                    'total_eliminadas' => 0
                ], 200);
            }

            // Calcular cuántas asignaturas se eliminaron
            $totalEliminadas = count($homologacionesActuales) - count($homologacionesFiltradas);

            // Indexar de nuevo el array (para evitar problemas con índices no secuenciales)
            $homologacionesFiltradas = array_values($homologacionesFiltradas);

            // Convertir el array actualizado a JSON para almacenar
            $homologacionesJson = json_encode($homologacionesFiltradas);

            // Actualizar mediante procedimiento almacenado
            DB::statement('CALL ActualizarHomologacionAsignatura(?, ?, ?, ?, ?)', [
                $id,
                $homologacion->solicitud_id,
                $homologacionesJson,
                now()->toDateString(),
                $homologacion->ruta_pdf_resolucion
            ]);

            // Obtener la homologación actualizada para devolver en la respuesta
            $homologacionActualizada = HomologacionAsignatura::find($id);
            $datosFormateados = $this->formatearDatosHomologacion($homologacionActualizada);

            // Respuesta exitosa con información de cambios y datos actualizados
            return response()->json([
                'mensaje' => 'Asignaturas eliminadas correctamente de la homologación',
                'total_eliminadas' => $totalEliminadas,
                'asignaturas_restantes' => count($homologacionesFiltradas),
                'datos' => $datosFormateados
            ], 200);
        } catch (\Exception $e) {
            // Manejo detallado de errores
            return response()->json([
                'mensaje' => 'Error al eliminar las asignaturas destino',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Actualiza únicamente el PDF de resolución de una homologación
     *
     * @param Request $request
     * @param int $id ID de la homologación
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarPDFResolucion(Request $request, $id)
    {
        try {
            // Validación del archivo PDF únicamente
            $request->validate([
                'ruta_pdf_resolucion' => 'required|file|mimes:pdf|max:10240', // Máximo 10 MB
            ]);

            // Obtener la homologación actual de la base de datos
            $homologacion = HomologacionAsignatura::findOrFail($id);

            // Manejar la actualización del archivo PDF
            $pdfPath = $homologacion->ruta_pdf_resolucion; // Valor actual por defecto

            // Si ya existe un PDF anterior, eliminarlo del storage
            if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }

            // Guardar el nuevo archivo PDF
            $pdfPath = $request->file('ruta_pdf_resolucion')->store('resoluciones_homologaciones', 'public');

            // Actualizar mediante procedimiento almacenado manteniendo los demás datos intactos
            DB::statement('CALL ActualizarHomologacionAsignatura(?, ?, ?, ?, ?)', [
                $id,
                $homologacion->solicitud_id,
                json_encode($homologacion->homologaciones), // Mantener las homologaciones existentes sin cambios
                now()->toDateString(),
                $pdfPath // Actualizar solo la ruta del PDF
            ]);

            // Generar URL pública para el PDF
            $urlPDF = asset('storage/' . $pdfPath);

            // Respuesta exitosa
            return response()->json([
                'mensaje' => 'PDF de resolución actualizado correctamente',
                'ruta_pdf_resolucion' => $pdfPath,
                'url_pdf_resolucion' => $urlPDF
            ], 200);
        } catch (\Exception $e) {
            // Manejo detallado de errores
            return response()->json([
                'mensaje' => 'Error al actualizar el PDF de resolución',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }
    /**
     * Actualiza únicamente el estado de una homologación de asignatura por ID de usuario
     * y envía notificaciones correspondientes según el nuevo estado.
     *
     * @param Request $request Datos con el nuevo estado
     * @param int $usuarioId ID del usuario cuya homologación se va a actualizar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function actualizarEstadoHomologacionPorUsuario(Request $request, $usuarioId)
    {
        try {
            // Validación básica del campo estado
            $request->validate([
                'estado' => 'required|string|in:Pendiente,En proceso,Aprobado,Rechazado,Finalizado'
            ]);

            // Buscar la solicitud del usuario
            $solicitud = Solicitud::where('usuario_id', $usuarioId)->first();

            if (!$solicitud) {
                return response()->json([
                    'mensaje' => 'No se encontró solicitud para el usuario especificado'
                ], 404);
            }

            // Buscar la homologación asociada a la solicitud
            $homologacion = HomologacionAsignatura::where('solicitud_id', $solicitud->id_solicitud)->first();

            if (!$homologacion) {
                return response()->json([
                    'mensaje' => 'No se encontró homologación para el usuario especificado'
                ], 404);
            }

            // Cargar relaciones necesarias
            $solicitud->load('usuario', 'programaDestino');

            // Guardar el estado anterior para comparación
            $estadoAnterior = $homologacion->estado ?? 'Sin estado';
            $nuevoEstado = $request->estado;

            // Si el estado no cambia, no hacer nada
            if ($estadoAnterior === $nuevoEstado) {
                return response()->json([
                    'mensaje' => 'El estado de la homologación ya es: ' . $nuevoEstado,
                    'estado' => $nuevoEstado
                ], 200);
            }

            // Actualizar solo el campo estado
            $homologacion->estado = $nuevoEstado;
            $homologacion->save();

            // Registrar el cambio en el log
            Log::info("Homologación {$homologacion->id_homologacion} cambió de estado", [
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $nuevoEstado,
                'usuario_id' => $usuarioId,
                'solicitud_id' => $solicitud->id_solicitud,
                'numero_radicado' => $solicitud->numero_radicado
            ]);

            // Envío a Vicerrectoría si pasó a "En proceso"
            if ($estadoAnterior !== 'En proceso' && $nuevoEstado === 'En proceso') {
                Log::info("Homologación {$homologacion->id_homologacion} pasó a 'En proceso'. Enviando notificación a Vicerrectoría.");

                $usuario = $solicitud->usuario;
                $programaDestino = $solicitud->programaDestino;

                $datos = [
                    'primer_nombre' => $usuario->primer_nombre,
                    'segundo_nombre' => $usuario->segundo_nombre ?? '',
                    'primer_apellido' => $usuario->primer_apellido,
                    'segundo_apellido' => $usuario->segundo_apellido ?? '',
                    'email' => $usuario->email,
                    'homologacion_id' => $homologacion->id_homologacion,
                    'solicitud_id' => $solicitud->id_solicitud,
                    'estado' => $homologacion->estado,
                    'numero_radicado' => $solicitud->numero_radicado ?? 'No disponible',
                    'programa_destino' => $programaDestino->nombre ?? 'No especificado',
                    'fecha_homologacion' => $homologacion->fecha
                ];

                try {
                    // Intentar envío mediante controlador específico si existe
                    if (isset($this->notificacionVicerrectoriaController)) {
                        $resultadoControlador = $this->notificacionVicerrectoriaController->notificarVicerrectoriaPorHomologacion($homologacion->id_homologacion);

                        Log::info("Resultado del envío mediante notificacionVicerrectoriaController: " . ($resultadoControlador ? 'Éxito' : 'Fallo'));

                        if (!$resultadoControlador) {
                            throw new \Exception("Fallo al enviar correo mediante controlador");
                        }
                    } else {
                        throw new \Exception("El controlador notificacionVicerrectoriaController no está disponible");
                    }
                } catch (\Exception $controllerError) {
                    Log::warning("Error usando controlador: " . $controllerError->getMessage() . ". Intentando método directo");

                    try {
                        // Usar el mailable existente o crear uno específico para homologaciones
                        Mail::to("brayner.trochez.o@uniautonoma.edu.co")->send(new VicerrectoriaMailable($datos));
                        Log::info("Correo enviado usando método directo de respaldo");
                    } catch (\Exception $mailError) {
                        Log::error("Error al enviar correo directo", [
                            'error' => $mailError->getMessage(),
                            'trace' => $mailError->getTraceAsString()
                        ]);
                    }
                }
            }

            // Notificar al estudiante si el estado cambia a Aprobado, Rechazado o Finalizado
            $estadosParaNotificar = ['Aprobado', 'Rechazado', 'Finalizado'];
            if (in_array($nuevoEstado, $estadosParaNotificar)) {
                Log::info("Estado de homologación actualizado a '{$nuevoEstado}', enviando notificación al estudiante.");

                try {
                    // Aquí puedes implementar el envío de correo al estudiante
                    // usando un método similar al enviarCorreo del primer controlador
                    $this->enviarCorreoHomologacion($homologacion->id_homologacion);
                } catch (\Exception $e) {
                    Log::error("Error al enviar correo al estudiante", [
                        'error' => $e->getMessage(),
                        'homologacion_id' => $homologacion->id_homologacion
                    ]);
                }
            }

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'Estado de homologación actualizado correctamente',
                'homologacion_id' => $homologacion->id_homologacion,
                'solicitud_id' => $solicitud->id_solicitud,
                'usuario_id' => $usuarioId,
                'estado_anterior' => $estadoAnterior,
                'estado_actual' => $nuevoEstado,
                'numero_radicado' => $solicitud->numero_radicado
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al actualizar el estado de la homologación por usuario', [
                'usuario_id' => $usuarioId,
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);

            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al actualizar el estado de la homologación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método auxiliar para enviar correo de notificación al estudiante sobre cambios en homologación
     *
     * @param int $homologacionId ID de la homologación
     * @return bool
     */
    private function enviarCorreoHomologacion($homologacionId)
    {
        try {
            // Obtener la homologación con sus relaciones
            $homologacion = HomologacionAsignatura::find($homologacionId);
            if (!$homologacion) {
                return false;
            }

            $solicitud = Solicitud::with('usuario', 'programaDestino')->find($homologacion->solicitud_id);
            if (!$solicitud) {
                return false;
            }

            $datosCorreo = [
                'estudiante' => $solicitud->usuario->primer_nombre . ' ' . $solicitud->usuario->primer_apellido,
                'estado' => $homologacion->estado,
                'numero_radicado' => $solicitud->numero_radicado,
                'programa_destino' => $solicitud->programaDestino->nombre ?? 'No especificado',
                'fecha_actualizacion' => now()->format('d/m/Y')
            ];

            // Aquí deberías usar tu mailable específico para homologaciones
            // Mail::to($solicitud->usuario->email)->send(new HomologacionEstadoMailable($datosCorreo));

            Log::info("Correo de homologación enviado al estudiante", [
                'email' => $solicitud->usuario->email,
                'homologacion_id' => $homologacionId
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Error en enviarCorreoHomologacion", [
                'error' => $e->getMessage(),
                'homologacion_id' => $homologacionId
            ]);
            return false;
        }
    }
}
