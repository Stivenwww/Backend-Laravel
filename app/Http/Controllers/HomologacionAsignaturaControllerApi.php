<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomologacionAsignaturaControllerApi extends Controller
{
    // Método para obtener todas las homologaciones de asignaturas
    public function traerHomologacionAsignaturas()
    {
        try {
            $homologaciones = DB::select('CALL ObtenerHomologacionesAsignaturas()');
            return response()->json($homologaciones);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener las homologaciones de asignaturas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener una homologación de asignatura por ID
    public function llevarHomologacionAsignatura($id)
    {
        try {
            $homologacion = DB::select('CALL ObtenerHomologacionAsignaturaPorId(?)', [$id]);

            if (!empty($homologacion)) {
                return response()->json([
                    'mensaje' => 'Homologación de asignatura encontrada',
                    'datos' => $homologacion[0]
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Homologación de asignatura no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener la homologación de asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar una nueva homologación de asignatura
    public function insertarHomologacionAsignatura(Request $request)
    {
        try {
            // Opcional: validaciones simples
            $request->validate([
                'solicitud_id' => 'required|integer',
                'asignatura_origen_id' => 'required|integer',
                'asignatura_destino_id' => 'nullable|integer',
                'nota_destino' => 'nullable|numeric',
                'comentarios' => 'nullable|string',
            ]);

            // Insertar llamando al procedimiento almacenado
            DB::statement('CALL InsertarHomologacionAsignatura(?, ?, ?, ?, ?, ?)', [
                $request->solicitud_id,
                $request->asignatura_origen_id,
                $request->asignatura_destino_id,
                $request->nota_destino,
                $request->comentarios,
                Carbon::now()->toDateString() // Insertamos la fecha actual
            ]);

            return response()->json([
                'mensaje' => 'Homologación de asignatura insertada correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar la homologación de asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar una homologación de asignatura
    public function actualizarHomologacionAsignatura(Request $request, $id)
    {
        try {
            $request->validate([
                'solicitud_id' => 'required|integer',
                'asignatura_origen_id' => 'required|integer',
                'asignatura_destino_id' => 'nullable|integer',
                'nota_destino' => 'nullable|numeric',
                'comentarios' => 'nullable|string',
            ]);

            DB::statement('CALL ActualizarHomologacionAsignatura(?, ?, ?, ?, ?, ?)', [
                $id,
                $request->solicitud_id,
                $request->asignatura_origen_id,
                $request->asignatura_destino_id,
                $request->nota_destino,
                $request->comentarios
            ]);

            return response()->json([
                'mensaje' => 'Homologación de asignatura actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar la homologación de asignatura',
                'error' => $e->getMessage()
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
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
