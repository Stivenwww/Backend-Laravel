<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultadControllerApi extends Controller
{
    /**
     * Método para obtener todas las facultades.
     * Utiliza un procedimiento almacenado para recuperar la lista completa de facultades.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function traerFacultades()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todas las facultades
            $facultades = DB::select('CALL ObtenerFacultades()');
            return response()->json($facultades);
        } catch (\Exception $e) {
            // Manejo de errores con mensaje descriptivo y código de estado 500
            return response()->json([
                'mensaje' => 'Error al obtener las facultades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para obtener una facultad específica por su ID.
     * Utiliza un procedimiento almacenado para buscar una facultad por su identificador.
     *
     * @param int $id ID de la facultad a buscar
     * @return \Illuminate\Http\JsonResponse
     */
    public function llevarFacultad($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener una facultad por ID
            $facultad = DB::select('CALL ObtenerFacultadPorId(?)', [$id]);

            // Verifica si se encontró la facultad
            if (!empty($facultad)) {
                return response()->json([
                    'mensaje' => 'Facultad encontrada',
                    'datos' => $facultad[0] // Accede al primer resultado del array
                ], 200);
            } else {
                // Responde con 404 si no se encuentra la facultad
                return response()->json([
                    'mensaje' => 'Facultad no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con mensaje descriptivo
            return response()->json([
                'mensaje' => 'Error al obtener la facultad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para insertar una nueva facultad.
     * Valida los datos de entrada y utiliza un procedimiento almacenado para la inserción.
     *
     * @param \Illuminate\Http\Request $request Datos de la facultad a insertar
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertarFacultad(Request $request)
    {
        try {
            // Valida que los datos cumplan con los requisitos antes de procesarlos
            $request->validate([
                'institucion_id' => 'required|integer|exists:instituciones,id_institucion', // Verifica que exista la institución
                'nombre'         => 'required|string|max:255',
            ]);

            // Llamada al procedimiento almacenado para insertar una nueva facultad
            DB::statement('CALL InsertarFacultad(?, ?)', [
                $request->institucion_id,
                $request->nombre
            ]);

            // Responde con código 201 (Created) si la inserción fue exitosa
            return response()->json([
                'mensaje' => 'Facultad insertada correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores, incluyendo errores de validación
            return response()->json([
                'mensaje' => 'Error al insertar la facultad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para actualizar una facultad existente.
     * Valida los datos de entrada y utiliza un procedimiento almacenado para la actualización.
     *
     * @param \Illuminate\Http\Request $request Nuevos datos de la facultad
     * @param int $id ID de la facultad a actualizar
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarFacultad(Request $request, $id)
    {
        try {
            // Valida los datos de entrada, asegurando que sean correctos antes de la actualización
            $request->validate([
                'institucion_id' => 'required|integer|exists:instituciones,id_institucion', // Verifica que exista la institución
                'nombre'         => 'required|string|max:255',
            ]);

            // Llamada al procedimiento almacenado para actualizar la facultad
            DB::statement('CALL ActualizarFacultad(?, ?, ?)', [
                $id,
                $request->institucion_id,
                $request->nombre
            ]);

            // Responde con código 200 (OK) si la actualización fue exitosa
            return response()->json([
                'mensaje' => 'Facultad actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores, incluyendo errores de validación
            return response()->json([
                'mensaje' => 'Error al actualizar la facultad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para eliminar una facultad.
     * Utiliza un procedimiento almacenado para eliminar la facultad por su ID.
     *
     * @param int $id ID de la facultad a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarFacultad($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar una facultad
            DB::statement('CALL EliminarFacultad(?)', [$id]);

            // Responde con código 200 (OK) si la eliminación fue exitosa
            return response()->json([
                'mensaje' => 'Facultad eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores, que pueden incluir restricciones de clave foránea
            return response()->json([
                'mensaje' => 'Error al eliminar la facultad',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
