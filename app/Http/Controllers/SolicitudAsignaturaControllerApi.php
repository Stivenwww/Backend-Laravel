<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudAsignaturaControllerApi extends Controller
{
    // Método para obtener todas las solicitudes de asignaturas
    public function traerSolicitudAsignaturas()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todas las solicitudes de asignaturas
            $solicitudes = DB::select('CALL ObtenerSolicitudAsignaturas()');
            return response()->json($solicitudes);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener las solicitudes de asignaturas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener una solicitud de asignatura por ID
    public function llevarSolicitudAsignatura($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener una solicitud de asignatura por ID
            $solicitud = DB::select('CALL ObtenerSolicitudAsignaturaPorId(?)', [$id]);

            if (!empty($solicitud)) {
                return response()->json([
                    'mensaje' => 'Solicitud de asignatura encontrada',
                    'datos' => $solicitud[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Solicitud de asignatura no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener la solicitud de asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar una nueva solicitud de asignatura
    public function insertarSolicitudAsignatura(Request $request)
    {
        try {
            // Validación de datos opcional
            $request->validate([
                'solicitud_id' => 'required|integer',
                'asignatura_id' => 'required|integer',
                'nota_origen' => 'nullable|numeric',
                'horas_sena' => 'nullable|integer',
            ]);

            // ¡Aquí falta la llamada al procedimiento
        DB::statement('CALL InsertarSolicitudAsignatura(?, ?, ?, ?)', [
            $request->solicitud_id,
            $request->asignatura_id,
            $request->nota_origen,
            $request->horas_sena,
        ]);

            return response()->json([
                'mensaje' => 'Solicitud de asignatura insertada correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar la solicitud de asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar una solicitud de asignatura
    public function actualizarSolicitudAsignatura(Request $request, $id)
    {
        try {
            // Validación de datos opcional
            $request->validate([
                'solicitud_id' => 'required|integer',
                'asignatura_id' => 'required|integer',
                'nota_origen' => 'nullable|numeric',
                'horas_sena' => 'nullable|integer',
            ]);

            // Llamada al procedimiento almacenado para actualizar una solicitud de asignatura
            DB::statement('CALL ActualizarSolicitudAsignatura(?, ?, ?, ?, ?)', [
                $id,
                $request->solicitud_id ?? null,
                $request->asignatura_id ?? null,
                $request->nota_origen ?? null,
                $request->horas_sena ?? null
            ]);

            return response()->json([
                'mensaje' => 'Solicitud de asignatura actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar la solicitud de asignatura',
                'error' => $e->getMessage()
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
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
