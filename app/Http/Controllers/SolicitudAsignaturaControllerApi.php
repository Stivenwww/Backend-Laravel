<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudAsignatura;
use App\Models\Asignatura;
use App\Models\Programa;
use App\Models\Facultad;
use App\Models\Institucion;
use App\Models\Solicitud;

class SolicitudAsignaturaControllerApi extends Controller
{
    // Método para obtener todas las solicitudes de asignaturas
    public function traerSolicitudAsignaturas()
    {
        try {
            // Llamada al procedimiento almacenado
            $solicitudes = DB::select('CALL ObtenerSolicitudAsignaturas()');
            $resultados = [];

            foreach ($solicitudes as $solicitud) {
                // Convertir datos a nuevo formato
                $resultados[] = $this->formatearDatosSolicitud($solicitud);
            }

            return response()->json($resultados);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener las solicitudes de asignaturas',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    // Método para obtener una solicitud de asignatura por ID
    public function llevarSolicitudAsignatura($id)
    {
        try {
            // Llamada al procedimiento almacenado
            $solicitud = DB::select('CALL ObtenerSolicitudAsignaturaPorId(?)', [$id]);

            if (!empty($solicitud)) {
                // Convertir a nuevo formato
                $datosFormateados = $this->formatearDatosSolicitud($solicitud[0]);

                return response()->json([
                    'mensaje' => 'Solicitud de asignatura encontrada',
                    'datos' => $datosFormateados
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Solicitud de asignatura no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener la solicitud de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    // Método para insertar una nueva solicitud de asignatura
    public function insertarSolicitudAsignatura(Request $request)
    {
        try {
            // Validación de datos
            $request->validate([
                'solicitud_id' => 'required|integer',
                'asignaturas' => 'required|array', // Ahora esperamos un array de asignaturas
                'asignaturas.*.asignatura_id' => 'required|integer',
                'asignaturas.*.nota_origen' => 'nullable|numeric',
                'asignaturas.*.horas_sena' => 'nullable|integer',
            ]);

            // Convertimos el array de asignaturas a formato JSON para almacenar
            $asignaturasJson = json_encode($request->asignaturas);

            // Insertar usando el procedimiento almacenado
            DB::statement('CALL InsertarSolicitudAsignatura(?, ?)', [
                $request->solicitud_id,
                $asignaturasJson
            ]);

            return response()->json([
                'mensaje' => 'Solicitud de asignatura insertada correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar la solicitud de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    // Método para actualizar una solicitud de asignatura
    public function actualizarSolicitudAsignatura(Request $request, $id)
    {
        try {
            // Validación de datos
            $request->validate([
                'solicitud_id' => 'required|integer',
                'asignaturas' => 'required|array', // Ahora esperamos un array de asignaturas
                'asignaturas.*.asignatura_id' => 'required|integer',
                'asignaturas.*.nota_origen' => 'nullable|numeric',
                'asignaturas.*.horas_sena' => 'nullable|integer',
            ]);

            // Convertimos el array de asignaturas a formato JSON para almacenar
            $asignaturasJson = json_encode($request->asignaturas);

            // Llamada al procedimiento almacenado para actualizar
            DB::statement('CALL ActualizarSolicitudAsignatura(?, ?, ?)', [
                $id,
                $request->solicitud_id,
                $asignaturasJson
            ]);

            return response()->json([
                'mensaje' => 'Solicitud de asignatura actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar la solicitud de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    // Método para eliminar una solicitud de asignatura
    public function eliminarSolicitudAsignatura($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar una solicitud
            DB::statement('CALL EliminarSolicitudAsignatura(?)', [$id]);

            return response()->json([
                'mensaje' => 'Solicitud de asignatura eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar la solicitud de asignatura',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Método para dar formato a los datos de solicitud de asignatura
     */
    private function formatearDatosSolicitud($solicitud)
    {
        // Datos base de la solicitud
        $resultado = [
            'id_solicitud_asignatura' => $solicitud->id_solicitud_asignatura ?? null,
            'solicitud_id' => $solicitud->solicitud_id ?? null,
            'numero_radicado' => $solicitud->numero_radicado ?? null,
            'estudiante' => $solicitud->estudiante ?? null,
            'institucion' => $solicitud->institucion ?? null,
            'created_at' => $solicitud->created_at ?? null,
            'updated_at' => $solicitud->updated_at ?? null,
            'asignaturas' => []
        ];

        // Decodificar las asignaturas
        $asignaturasArray = json_decode($solicitud->asignaturas ?? '[]', true);

        if (!is_array($asignaturasArray)) {
            return $resultado;
        }

        // Procesar cada asignatura
        foreach ($asignaturasArray as $asignaturaItem) {
            $asignaturaId = $asignaturaItem['asignatura_id'] ?? 0;

            if (empty($asignaturaId)) {
                continue;
            }

            // Obtener información detallada de la asignatura
            $infoAsignatura = $this->obtenerInfoAsignatura($asignaturaId);

            // Añadir nota_origen o horas_sena según corresponda
            if (isset($asignaturaItem['nota_origen']) && $asignaturaItem['nota_origen'] !== null) {
                $infoAsignatura['nota_origen'] = $asignaturaItem['nota_origen'];
            } else {
                $infoAsignatura['nota_origen'] = null;
            }

            if (isset($asignaturaItem['horas_sena']) && $asignaturaItem['horas_sena'] !== null) {
                $infoAsignatura['horas_sena'] = $asignaturaItem['horas_sena'];
            } else {
                $infoAsignatura['horas_sena'] = null;
            }

            // Añadir a las asignaturas del resultado
            $resultado['asignaturas'][] = $infoAsignatura;
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
