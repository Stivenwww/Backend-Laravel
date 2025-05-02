<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    // Método para obtener todas las homologaciones de asignaturas
    public function traerHomologacionAsignaturas()
    {
        try {
            $homologaciones = DB::select('CALL ObtenerHomologacionesAsignaturas()');
            $resultados = [];

            foreach ($homologaciones as $homologacion) {
                // Convertir datos a nuevo formato
                $resultados[] = $this->formatearDatosHomologacion($homologacion);
            }

            return response()->json($resultados);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener las homologaciones de asignaturas',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    // Método para obtener una homologación de asignatura por ID
    public function llevarHomologacionAsignatura($id)
    {
        try {
            $homologacion = DB::select('CALL ObtenerHomologacionAsignaturaPorId(?)', [$id]);

            if (!empty($homologacion)) {
                // Convertir a nuevo formato
                $datosFormateados = $this->formatearDatosHomologacion($homologacion[0]);

                return response()->json([
                    'mensaje' => 'Homologación de asignatura encontrada',
                    'datos' => $datosFormateados
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Homologación de asignatura no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener la homologación de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    // Método para insertar una nueva homologación de asignatura
    // Método para insertar una nueva homologación de asignatura
public function insertarHomologacionAsignatura(Request $request)
{
    try {
        // Validamos los datos
        $request->validate([
            'solicitud_id' => 'required|integer',
            'asignaturas_origen' => 'required|array',
            'asignaturas_origen.*' => 'required|integer',
        ]);

        // Obtenemos las asignaturas de origen desde el request
        $asignaturasOrigen = $request->asignaturas_origen;

        // Creamos la estructura de homologaciones con destinos vacíos
        $homologaciones = [];
        foreach ($asignaturasOrigen as $asignaturaOrigenId) {
            $homologaciones[] = [
                'asignatura_origen_id' => $asignaturaOrigenId,
                'asignatura_destino_id' => null,
                'nota_destino' => null,
                'comentarios' => null
            ];
        }

        // Convertimos el array de homologaciones a formato JSON para almacenar
        $homologacionesJson = json_encode($homologaciones);

        // Insertar llamando al procedimiento almacenado
        DB::statement('CALL InsertarHomologacionAsignatura(?, ?, ?)', [
            $request->solicitud_id,
            $homologacionesJson,
            Carbon::now()->toDateString()
        ]);

        return response()->json([
            'mensaje' => 'Homologación de asignatura insertada correctamente',
            'datos' => [
                'solicitud_id' => $request->solicitud_id,
                'homologaciones' => $homologaciones
            ]
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'mensaje' => 'Error al insertar la homologación de asignatura',
            'error' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile()
        ], 500);
    }
}

    // Método para actualizar múltiples asignaturas destino en una homologación con una sola petición
public function actualizarHomologacionAsignatura(Request $request, $id)
{
    try {
        $request->validate([
            'homologaciones' => 'required|array',
            'homologaciones.*.asignatura_origen_id' => 'required|integer',
            'homologaciones.*.asignatura_destino_id' => 'nullable|integer',
            'homologaciones.*.nota_destino' => 'nullable|numeric',
            'homologaciones.*.comentarios' => 'nullable|string',
        ]);

        // Obtenemos la homologación actual
        $homologacion = HomologacionAsignatura::findOrFail($id);

        // Decodificamos las homologaciones existentes
        $homologacionesActuales = $homologacion->homologaciones;

        // Creamos un mapeo de origen_id => índice para buscar rápidamente
        $mapeoIndices = [];
        foreach ($homologacionesActuales as $key => $homologacionItem) {
            $mapeoIndices[$homologacionItem['asignatura_origen_id']] = $key;
        }

        // Asignaturas actualizadas y no encontradas
        $actualizadas = [];
        $noEncontradas = [];

        // Procesamos cada homologación del request
        foreach ($request->homologaciones as $homologacionRequest) {
            $origenId = $homologacionRequest['asignatura_origen_id'];

            // Verificamos si la asignatura origen existe en la homologación actual
            if (isset($mapeoIndices[$origenId])) {
                $index = $mapeoIndices[$origenId];

                // Actualizamos solo los campos de destino
                $homologacionesActuales[$index]['asignatura_destino_id'] = $homologacionRequest['asignatura_destino_id'];
                $homologacionesActuales[$index]['nota_destino'] = $homologacionRequest['nota_destino'];
                $homologacionesActuales[$index]['comentarios'] = $homologacionRequest['comentarios'] ?? null;

                $actualizadas[] = $origenId;
            } else {
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

        // Convertimos a JSON para almacenar
        $homologacionesJson = json_encode($homologacionesActuales);

        // Actualizamos usando el procedimiento almacenado
        DB::statement('CALL ActualizarHomologacionAsignatura(?, ?, ?, ?)', [
            $id,
            $homologacion->solicitud_id,
            $homologacionesJson,
            now()->toDateString()
        ]);

        return response()->json([
            'mensaje' => 'Homologación de asignaturas actualizada correctamente',
            'asignaturas_actualizadas' => $actualizadas,
            'total_actualizadas' => count($actualizadas)
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'mensaje' => 'Error al actualizar la homologación de asignatura',
            'error' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile()
        ], 500);
    }
}

    // Método para eliminar una homologación de asignatura
    public function eliminarHomologacionAsignatura($id)
    {
        try {
            DB::statement('CALL EliminarHomologacionAsignatura(?)', [$id]);

            return response()->json([
                'mensaje' => 'Homologación de asignatura eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar la homologación de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Método para dar formato a los datos de homologación en la estructura solicitada
     */
    private function formatearDatosHomologacion($homologacion)
    {
        try {
            // Obtener información de la solicitud
            $solicitud = Solicitud::find($homologacion->solicitud_id);

            // Información del estudiante
            $estudiante = User::find($solicitud->usuario_id ?? 0);

            // Obtener programa de destino del estudiante
            $programaDestino = Programa::find($solicitud->programa_destino_id ?? 0);

            // Datos base de la solicitud
            $resultado = [
                'id_homologacion' => $homologacion->id_homologacion,
                'solicitud_id' => $homologacion->solicitud_id,
                'numero_radicado' => $homologacion->numero_radicado,
                'estudiante' => $homologacion->estudiante,
                'numero_identificacion' => $estudiante->numero_identificacion ?? 'No disponible',
                'programa_destino' => $programaDestino->nombre ?? 'No disponible',
                'estado_solicitud' => $solicitud->estado ?? 'No disponible',
                'fecha' => $homologacion->fecha,
                'asignaturas_origen' => [],
                'asignaturas_destino' => [],
                'comentarios' => null  // Un solo comentario para toda la homologación, inicializado como null
            ];

            // Verificar si homologaciones es una cadena JSON o ya es un array
            $homologacionesArray = null;
            if (is_string($homologacion->homologaciones)) {
                // Es una cadena - intentar decodificar
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

            if (!is_array($homologacionesArray)) {
                return $resultado;
            }

            // Obtener la información de la solicitud para saber si es SENA
            try {
                $solicitudAsignatura = SolicitudAsignatura::where('solicitud_id', $homologacion->solicitud_id)->first();
                $esSena = false;
                $asignaturasOrigen = [];

                if ($solicitud && $solicitudAsignatura) {
                    // Verificar si asignaturas es una cadena JSON o ya es un array
                    if (is_string($solicitudAsignatura->asignaturas)) {
                        $asignaturasOrigen = json_decode($solicitudAsignatura->asignaturas, true);
                    } elseif (is_array($solicitudAsignatura->asignaturas)) {
                        $asignaturasOrigen = $solicitudAsignatura->asignaturas;
                    } elseif (is_object($solicitudAsignatura->asignaturas)) {
                        $asignaturasOrigen = json_decode(json_encode($solicitudAsignatura->asignaturas), true);
                    }

                    if (is_array($asignaturasOrigen) && count($asignaturasOrigen) > 0) {
                        // Si cualquiera de las asignaturas tiene horas_sena, asumimos que es SENA
                        foreach ($asignaturasOrigen as $asignatura) {
                            if (isset($asignatura['horas_sena']) && $asignatura['horas_sena'] !== null) {
                                $esSena = true;
                                break;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $esSena = false;
                $asignaturasOrigen = [];
            }

            // Procesar cada homologación
            foreach ($homologacionesArray as $homologacionItem) {
                // Obtener información de asignatura origen
                $asignaturaOrigenId = $homologacionItem['asignatura_origen_id'] ?? 0;
                $asignaturaDestinoId = $homologacionItem['asignatura_destino_id'] ?? null;

                // Buscar la asignatura origen en la solicitud original para obtener nota_origen o horas_sena
                $asignaturaOrigen = $this->obtenerInfoAsignatura($asignaturaOrigenId);

                // Buscar contenido programático de la asignatura origen
                $asignaturaOrigen['contenido_programatico'] = $this->obtenerContenidoProgramatico($asignaturaOrigenId);

                // Establecer universidad_origen en el resultado principal basado en la primera asignatura de origen
                if (empty($resultado['universidad_origen']) && !empty($asignaturaOrigen['institucion']) && $asignaturaOrigen['institucion'] != 'No disponible') {
                    $resultado['universidad_origen'] = $asignaturaOrigen['institucion'];
                }

                if (isset($asignaturasOrigen) && is_array($asignaturasOrigen)) {
                    foreach ($asignaturasOrigen as $asignaturaSol) {
                        if (isset($asignaturaSol['asignatura_id']) && $asignaturaSol['asignatura_id'] == $asignaturaOrigenId) {
                            if ($esSena && isset($asignaturaSol['horas_sena'])) {
                                $asignaturaOrigen['horas_sena'] = $asignaturaSol['horas_sena'];
                            } else if (isset($asignaturaSol['nota_origen'])) {
                                $asignaturaOrigen['nota_origen'] = $asignaturaSol['nota_origen'];
                            }
                            // Añadir créditos si existen
                            if (isset($asignaturaSol['creditos'])) {
                                $asignaturaOrigen['creditos'] = $asignaturaSol['creditos'];
                            }
                            break;
                        }
                    }
                }

                // Eliminar campos innecesarios basados en las condiciones
                if (!$esSena) {
                    unset($asignaturaOrigen['horas_sena']);
                }

                // Añadir asignatura origen al resultado
                $resultado['asignaturas_origen'][] = $asignaturaOrigen;

                // Obtener información de asignatura destino
                $asignaturaDestino = null;
                if ($asignaturaDestinoId) {
                    $asignaturaDestino = $this->obtenerInfoAsignatura($asignaturaDestinoId);
                    // Añadir nota de destino
                    $asignaturaDestino['nota_destino'] = $homologacionItem['nota_destino'] ?? null;
                    // Eliminar nota_origen ya que no es relevante en destino
                    unset($asignaturaDestino['nota_origen']);
                    // Buscar contenido programático de la asignatura destino
                    $asignaturaDestino['contenido_programatico'] = $this->obtenerContenidoProgramatico($asignaturaDestinoId);

                    // Eliminar campos innecesarios basados en las condiciones
                    if (!$esSena) {
                        unset($asignaturaDestino['horas_sena']);
                    }
                } else {
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

                // Agregar los comentarios individuales
                if (isset($homologacionItem['comentarios']) && !empty($homologacionItem['comentarios'])) {
                    if ($resultado['comentarios'] === null) {
                        $resultado['comentarios'] = [];
                    }
                    $resultado['comentarios'][] = [
                        'asignatura_origen_id' => $asignaturaOrigenId,
                        'asignatura_destino_id' => $asignaturaDestinoId,
                        'comentario' => $homologacionItem['comentarios']
                    ];
                }
            }

            return $resultado;
        } catch (\Exception $e) {
            // En caso de error, devolver al menos los datos básicos
            return [
                'id_homologacion' => $homologacion->id_homologacion ?? 0,
                'solicitud_id' => $homologacion->solicitud_id ?? 0,
                'numero_radicado' => $homologacion->numero_radicado ?? 'No disponible',
                'estudiante' => $homologacion->estudiante ?? 'No disponible',
                'fecha' => $homologacion->fecha ?? null,
                'error' => 'Error al formatear datos: ' . $e->getMessage(),
                'asignaturas_origen' => [],
                'asignaturas_destino' => []
            ];
        }
    }

    /**
     * Método para obtener información detallada de una asignatura
     */
    private function obtenerInfoAsignatura($asignaturaId)
    {
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

        if (empty($asignaturaId)) {
            return $info;
        }

        try {
            $asignatura = Asignatura::find($asignaturaId);

            if (!$asignatura) {
                return $info;
            }

            $info['nombre'] = $asignatura->nombre ?? 'No disponible';
            $info['codigo'] = $asignatura->codigo_asignatura ?? 'N/A';
            $info['semestre'] = $asignatura->semestre ?? 0;
            $info['creditos'] = $asignatura->creditos ?? null;

            // Obtener programa
            try {
                if ($asignatura->programa_id) {
                    $programa = Programa::find($asignatura->programa_id);
                    if ($programa) {
                        $info['programa'] = $programa->nombre ?? 'No disponible';

                        // Obtener facultad
                        if ($programa->facultad_id) {
                            $facultad = Facultad::find($programa->facultad_id);
                            if ($facultad) {
                                $info['facultad'] = $facultad->nombre ?? 'No disponible';
                            }
                        }

                        // Obtener institución
                        if ($programa->institucion_id) {
                            $institucion = Institucion::find($programa->institucion_id);
                            if ($institucion) {
                                $info['institucion'] = $institucion->nombre ?? 'No disponible';
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Silenciar error
            }
        } catch (\Exception $e) {
            // Silenciar error
        }

        return $info;
    }

    /**
     * Método para obtener el contenido programático de una asignatura
     */
    private function obtenerContenidoProgramatico($asignaturaId)
    {
        if (empty($asignaturaId)) {
            return null;
        }

        try {
            // Obtener contenido programático
            $contenidoProgramatico = ContenidoProgramatico::where('asignatura_id', $asignaturaId)->first();

            if (!$contenidoProgramatico) {
                return null;
            }

            // Devolvemos los campos según la estructura real de la tabla
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
     * Método para obtener todas las asignaturas que pertenecen a la Universidad Autónoma
     */
    public function obtenerAsignaturasAutonoma()
    {
        try {
            // Primero identificamos la institución Autónoma
            $institucion = Institucion::where('nombre', 'like', '%autónoma%')
                                      ->orWhere('nombre', 'like', '%autonoma%')
                                      ->first();

            if (!$institucion) {
                return response()->json([
                    'mensaje' => 'No se encontró la institución Autónoma',
                    'asignaturas' => []
                ], 404);
            }

            // Obtenemos los programas de la institución
            $programasIds = Programa::where('institucion_id', $institucion->id)->pluck('id')->toArray();

            if (empty($programasIds)) {
                return response()->json([
                    'mensaje' => 'No se encontraron programas para la institución Autónoma',
                    'asignaturas' => []
                ], 404);
            }

            // Obtenemos las asignaturas de esos programas
            $asignaturas = Asignatura::whereIn('programa_id', $programasIds)
                                     ->with(['programa', 'programa.facultad', 'programa.institucion'])
                                     ->get();

            // Formateamos las asignaturas
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
            return response()->json([
                'mensaje' => 'Error al obtener las asignaturas de la Universidad Autónoma',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }
}
