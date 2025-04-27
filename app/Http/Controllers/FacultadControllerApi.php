<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacultadControllerApi extends Controller
{
    // Método para obtener todas las facultades
    public function traerFacultades()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todas las facultades
            $facultades = DB::select('CALL ObtenerFacultades()');
            return response()->json($facultades);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener las facultades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener una facultad por ID
    public function llevarFacultad($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener una facultad por ID
            $facultad = DB::select('CALL ObtenerFacultadPorId(?)', [$id]);

            if (!empty($facultad)) {
                return response()->json([
                    'mensaje' => 'Facultad encontrada',
                    'datos' => $facultad[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Facultad no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener la facultad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar una nueva facultad
    public function insertarFacultad(Request $request)
    {
        try {

            $request->validate([
                'institucion_id' => 'required|integer|exists:instituciones,id_institucion',
                'nombre'         => 'required|string|max:255',
              ]);

            // Llamada al procedimiento almacenado para insertar una nueva facultad
            DB::statement('CALL InsertarFacultad(?, ?)', [
                $request->institucion_id,
                $request->nombre
            ]);

            return response()->json([
                'mensaje' => 'Facultad insertada correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar la facultad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar una facultad
    public function actualizarFacultad(Request $request, $id)
    {
        try {

            $request->validate([
                'institucion_id' => 'required|integer|exists:instituciones,id_institucion',
                'nombre'         => 'required|string|max:255',
              ]);
            // Llamada al procedimiento almacenado para actualizar una facultad
            DB::statement('CALL ActualizarFacultad(?, ?, ?)', [
                $id,
                $request->institucion_id,
                $request->nombre
            ]);

            return response()->json([
                'mensaje' => 'Facultad actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar la facultad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar una facultad
    public function eliminarFacultad($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar una facultad
            DB::statement('CALL EliminarFacultad(?)', [$id]);

            return response()->json([
                'mensaje' => 'Facultad eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar la facultad',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
