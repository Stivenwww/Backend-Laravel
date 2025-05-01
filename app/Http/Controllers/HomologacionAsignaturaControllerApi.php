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
    public function insertarHomologacionAsignatura(Request $request)
    {
        try {
            // Validamos los datos
            $request->validate([
                'solicitud_id' => 'required|integer',
                'homologaciones' => 'required|array',
                'homologaciones.*.asignatura_origen_id' => 'required|integer',
                'homologaciones.*.asignatura_destino_id' => 'nullable|integer',
                'homologaciones.*.nota_destino' => 'nullable|numeric',
                'homologaciones.*.comentarios' => 'nullable|string',
            ]);

            // Convertimos el array de homologaciones a formato JSON para almacenar
            $homologacionesJson = json_encode($request->homologaciones);

            // Insertar llamando al procedimiento almacenado
            DB::statement('CALL InsertarHomologacionAsignatura(?, ?, ?)', [
                $request->solicitud_id,
                $homologacionesJson,
                Carbon::now()->toDateString()
            ]);

            return response()->json([
                'mensaje' => 'Homologación de asignatura insertada correctamente'
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

    // Método para actualizar una homologación de asignatura
    public function actualizarHomologacionAsignatura(Request $request, $id)
    {
        try {
            $request->validate([
                'solicitud_id' => 'required|integer',
                'homologaciones' => 'required|array',
                'homologaciones.*.asignatura_origen_id' => 'required|integer',
                'homologaciones.*.asignatura_destino_id' => 'nullable|integer',
                'homologaciones.*.nota_destino' => 'nullable|numeric',
                'homologaciones.*.comentarios' => 'nullable|string',
            ]);

            // Convertimos el array de homologaciones a formato JSON para almacenar
            $homologacionesJson = json_encode($request->homologaciones);

            DB::statement('CALL ActualizarHomologacionAsignatura(?, ?, ?)', [
                $id,
                $request->solicitud_id,
                $homologacionesJson
            ]);

            return response()->json([
                'mensaje' => 'Homologación de asignatura actualizada correctamente'
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
        // Datos base de la solicitud
        $resultado = [
            'id_homologacion' => $homologacion->id_homologacion,
            'solicitud_id' => $homologacion->solicitud_id,
            'numero_radicado' => $homologacion->numero_radicado,
            'estudiante' => $homologacion->estudiante,
            'fecha' => $homologacion->fecha,
            'created_at' => $homologacion->created_at,
            'updated_at' => $homologacion->updated_at,
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
            $solicitud = Solicitud::find($homologacion->solicitud_id);
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

            // Buscar la asignatura origen en la solicitud original para obtener nota_origen o horas_sena
            $asignaturaOrigen = $this->obtenerInfoAsignatura($asignaturaOrigenId);

            if (isset($asignaturasOrigen) && is_array($asignaturasOrigen)) {
                foreach ($asignaturasOrigen as $asignaturaSol) {
                    if (isset($asignaturaSol['asignatura_id']) && $asignaturaSol['asignatura_id'] == $asignaturaOrigenId) {
                        if ($esSena && isset($asignaturaSol['horas_sena'])) {
                            $asignaturaOrigen['horas_sena'] = $asignaturaSol['horas_sena'];
                        } else if (isset($asignaturaSol['nota_origen'])) {
                            $asignaturaOrigen['nota_origen'] = $asignaturaSol['nota_origen'];
                        }
                        break;
                    }
                }
            }

            // Añadir asignatura origen al resultado
            $resultado['asignaturas_origen'][] = $asignaturaOrigen;

            // Añadir asignatura destino como null
            $resultado['asignaturas_destino'][] = [
                'id' => null,
                'nombre' => null,
                'codigo' => null,
                'semestre' => null,
                'programa' => null,
                'facultad' => null,
                'institucion' => null,
                'nota_origen' => null,
                'horas_sena' => null
            ];
        }

        return $resultado;
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
}
