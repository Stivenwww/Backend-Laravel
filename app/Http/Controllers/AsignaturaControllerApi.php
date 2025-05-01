<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignaturaControllerApi extends Controller
{
    // Método para obtener todas las asignaturas
    public function traerAsignaturas()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todas las asignaturas
            $asignaturas = DB::select('CALL ObtenerAsignaturas()');
            return response()->json($asignaturas);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener las asignaturas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener una asignatura por ID
    public function llevarAsignatura($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener una asignatura por ID
            $asignatura = DB::select('CALL ObtenerAsignaturaPorId(?)', [$id]);

            if (!empty($asignatura)) {
                return response()->json([
                    'mensaje' => 'Asignatura encontrada',
                    'datos' => $asignatura[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Asignatura no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar una nueva asignatura
    public function insertarAsignatura(Request $request)
    {
        try {
            // Llamada al procedimiento almacenado para insertar una nueva asignatura
            DB::statement('CALL InsertarAsignatura(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $request->programa_id,
                $request->nombre,
                $request->tipo,
                $request->codigo_asignatura,
                $request->creditos,
                $request->semestre,
                $request->horas_sena,
                $request->tiempo_presencial,
                $request->tiempo_independiente,
                $request->horas_totales_semanales,
                $request->modalidad,
                $request->metodologia
            ]);

            return response()->json([
                'mensaje' => 'Asignatura insertada correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar una asignatura
    public function actualizarAsignatura(Request $request, $id)
    {
        try {
            // Llamada al procedimiento almacenado para actualizar una asignatura
            DB::statement('CALL ActualizarAsignatura(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $request->programa_id,
                $request->nombre,
                $request->tipo,
                $request->codigo_asignatura,
                $request->creditos,
                $request->semestre,
                $request->horas_sena,
                $request->tiempo_presencial,
                $request->tiempo_independiente,
                $request->horas_totales_semanales,
                $request->modalidad,
                $request->metodologia
            ]);

            return response()->json([
                'mensaje' => 'Asignatura actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar una asignatura
    public function eliminarAsignatura($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar una asignatura
            DB::statement('CALL EliminarAsignatura(?)', [$id]);

            return response()->json([
                'mensaje' => 'Asignatura eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene todas las asignaturas pertenecientes a un programa específico
     *
     * @param int $id_programa ID del programa
     * @return \Illuminate\Http\JsonResponse
     */
    public function traerAsignaturasPorPrograma($id_programa)
    {
        try {
            // Obtener las asignaturas y cargar el nombre del programa
            $asignaturas = Asignatura::where('programa_id', $id_programa)
                ->with('programa')  // Cargar la relación del programa
                ->get();

            // Formatear la respuesta para incluir el nombre del programa y las asignaturas
            $asignaturasConPrograma = $asignaturas->map(function ($asignatura) {
                return [
                    'nombre_programa' => $asignatura->programa->nombre,  // Suponiendo que el campo del programa es 'nombre'
                    'id_asignatura' => $asignatura->id,
                    'programa_id' => $asignatura->programa_id,
                    'nombre' => $asignatura->nombre,
                    'tipo' => $asignatura->tipo,
                    'codigo_asignatura' => $asignatura->codigo_asignatura,
                    'creditos' => $asignatura->creditos,
                    'semestre' => $asignatura->semestre,
                    'horas_sena' => $asignatura->horas_sena,
                    'tiempo_presencial' => $asignatura->tiempo_presencial,
                    'tiempo_independiente' => $asignatura->tiempo_independiente,
                    'horas_totales_semanales' => $asignatura->horas_totales_semanales,
                    'modalidad' => $asignatura->modalidad,
                    'metodologia' => $asignatura->metodologia,
                    'created_at' => $asignatura->created_at,
                    'updated_at' => $asignatura->updated_at,
                ];
            });

            return response()->json([
                'data' => $asignaturasConPrograma,
                'message' => 'Asignaturas del programa recuperadas correctamente',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al recuperar las asignaturas: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }


    /**
     * Obtiene una asignatura específica de un programa específico
     *
     * @param int $id_programa ID del programa
     * @param int $id_asignatura ID de la asignatura
     * @return \Illuminate\Http\JsonResponse
     */
    public function llevarAsignaturaPorPrograma($id_programa, $id_asignatura)
    {
        try {
            // Obtener la asignatura y cargar el programa relacionado
            $asignatura = Asignatura::where('programa_id', $id_programa)
                ->where('id_asignatura', $id_asignatura)
                ->with('programa')  // Cargar la relación del programa
                ->first();

            if (!$asignatura) {
                return response()->json([
                    'message' => 'Asignatura no encontrada en este programa',
                    'success' => false
                ], 404);
            }

            // Estructurar la respuesta
            return response()->json([
                'data' => [
                    'nombre_programa' => $asignatura->programa->nombre,  // Suponiendo que 'nombre' es el campo del programa
                    'id_asignatura' => $asignatura->id,
                    'programa_id' => $asignatura->programa_id,
                    'nombre' => $asignatura->nombre,
                    'tipo' => $asignatura->tipo,
                    'codigo_asignatura' => $asignatura->codigo_asignatura,
                    'creditos' => $asignatura->creditos,
                    'semestre' => $asignatura->semestre,
                    'horas_sena' => $asignatura->horas_sena,
                    'tiempo_presencial' => $asignatura->tiempo_presencial,
                    'tiempo_independiente' => $asignatura->tiempo_independiente,
                    'horas_totales_semanales' => $asignatura->horas_totales_semanales,
                    'modalidad' => $asignatura->modalidad,
                    'metodologia' => $asignatura->metodologia,
                    'created_at' => $asignatura->created_at,
                    'updated_at' => $asignatura->updated_at,
                ],
                'message' => 'Asignatura recuperada correctamente',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al recuperar la asignatura: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
