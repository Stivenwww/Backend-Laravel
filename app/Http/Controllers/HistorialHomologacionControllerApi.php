<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistorialHomologacionControllerApi extends Controller
{
    /**
     * Método para obtener todos los registros del historial de homologaciones.
     * Utiliza un procedimiento almacenado para recuperar la lista completa.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function traerHistorialHomologaciones()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todos los historiales de homologaciones
            $historialHomologaciones = DB::select('CALL ObtenerHistorialHomologaciones()');
            return response()->json($historialHomologaciones);
        } catch (\Exception $e) {
            // Manejo de errores con mensaje descriptivo y código de estado 500
            return response()->json([
                'mensaje' => 'Error al obtener el historial de homologaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para obtener un registro específico del historial de homologaciones por su ID.
     * Utiliza un procedimiento almacenado para la búsqueda.
     *
     * @param int $id ID del registro a buscar
     * @return \Illuminate\Http\JsonResponse
     */
    public function llevarHistorialHomologacion($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener un historial específico por ID
            $historialHomologacion = DB::select('CALL ObtenerHistorialHomologacionPorId(?)', [$id]);

            // Verifica si se encontró el registro
            if (!empty($historialHomologacion)) {
                return response()->json([
                    'mensaje' => 'Historial de Homologaciones encontrado',
                    'datos' => $historialHomologacion[0] // Accede al primer resultado del array
                ], 200);
            } else {
                // Responde con 404 si no se encuentra el registro
                return response()->json([
                    'mensaje' => 'Historial de Homologacion no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con mensaje descriptivo
            return response()->json([
                'mensaje' => 'Error al obtener el historial de homologación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para insertar un nuevo registro en el historial de homologaciones.
     * Utiliza un procedimiento almacenado para la inserción.
     *
     * @param \Illuminate\Http\Request $request Datos del registro a insertar
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertarHistorialHomologacion(Request $request)
    {
        try {
            // Validación de datos de entrada
            $request->validate([
                'solicitud_id' => 'required|integer|exists:solicitudes,id', // Asumiendo que existe una tabla 'solicitudes'
                'usuario_id' => 'required|integer|exists:users,id',
                'estado' => 'required|string|max:50',
                'observaciones' => 'nullable|string',
                'ruta_pdf_resolucion' => 'nullable|string|max:255',
            ]);

            // Llamada al procedimiento almacenado para insertar un nuevo historial
            DB::statement('CALL InsertarHistorialHomologacion(?, ?, ?, ?, ?)', [
                $request->solicitud_id,
                $request->usuario_id,
                $request->estado,
                $request->observaciones,
                $request->ruta_pdf_resolucion
            ]);

            // Responde con código 201 (Created) si la inserción fue exitosa
            return response()->json(['mensaje' => 'Historial de Homologacion insertado correctamente'], 201);
        } catch (\Exception $e) {
            // Manejo de errores, incluyendo errores de validación
            return response()->json([
                'mensaje' => 'Error al insertar el historial de homologación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para actualizar un registro existente en el historial de homologaciones.
     * Utiliza un procedimiento almacenado para la actualización.
     *
     * @param \Illuminate\Http\Request $request Nuevos datos del registro
     * @param int $id ID del registro a actualizar
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarHistorialHomologacion(Request $request, $id)
    {
        try {
            // Validación de datos de entrada
            $request->validate([
                'solicitud_id' => 'required|integer|exists:solicitudes,id', // Asumiendo que existe una tabla 'solicitudes'
                'usuario_id' => 'required|integer|exists:users,id',
                'estado' => 'required|string|max:50',
                'observaciones' => 'nullable|string',
                'ruta_pdf_resolucion' => 'nullable|string|max:255',
            ]);

            // Llamada al procedimiento almacenado para actualizar un historial
            DB::statement('CALL ActualizarHistorialHomologacion(?, ?, ?, ?, ?, ?)', [
                $id,
                $request->solicitud_id,
                $request->usuario_id,
                $request->estado,
                $request->observaciones,
                $request->ruta_pdf_resolucion
            ]);

            // Responde con código 200 (OK) si la actualización fue exitosa
            return response()->json(['mensaje' => 'Historial de Homologacion actualizado correctamente'], 200);
        } catch (\Exception $e) {
            // Manejo de errores, incluyendo errores de validación
            return response()->json([
                'mensaje' => 'Error al actualizar el historial de homologación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para eliminar un registro del historial de homologaciones.
     * Utiliza un procedimiento almacenado para la eliminación.
     *
     * @param int $id ID del registro a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarHistorialHomologacion($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar un historial
            DB::statement('CALL EliminarHistorialHomologacion(?)', [$id]);

            // Responde con código 200 (OK) si la eliminación fue exitosa
            return response()->json(['mensaje' => 'Historial de Homologacion eliminado correctamente'], 200);
        } catch (\Exception $e) {
            // Manejo de errores, que pueden incluir restricciones de clave foránea
            return response()->json([
                'mensaje' => 'Error al eliminar el historial de homologación',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
