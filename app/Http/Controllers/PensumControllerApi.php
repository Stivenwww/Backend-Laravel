<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador API para gestionar pensums
 * Utiliza procedimientos almacenados para operaciones CRUD
 */
class PensumControllerApi extends Controller
{
    /**
     * Trae todos los pensums
     */
    public function traerPensums()
    {
        try {
            $pensums = DB::select('CALL ObtenerPensums()');

            return response()->json($pensums);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener los pensums',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trae un pensum por ID
     */
    public function llevarPensum($id)
    {
        try {
            $pensum = DB::select('CALL ObtenerPensumPorId(?)', [$id]);

            if (!empty($pensum)) {
                return response()->json([
                    'mensaje' => 'Pensum encontrado',
                    'datos' => $pensum[0]
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Pensum no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener el pensum',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Inserta un nuevo pensum
     */
    public function insertarPensum(Request $request)
    {
        try {
            $request->validate([
                'programa_id' => 'required|integer|exists:programas,id_programa',
                'anio' => 'required|digits:4|integer|min:2000',
            ]);

            DB::statement('CALL InsertarPensum(?, ?)', [
                $request->programa_id,
                $request->anio,
            ]);

            return response()->json([
                'mensaje' => 'Pensum insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar el pensum',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un pensum
     */
    public function actualizarPensum(Request $request, $id)
    {
        try {
            $request->validate([
                'programa_id' => 'required|integer|exists:programas,id_programa',
                'anio' => 'required|digits:4|integer|min:2000',
            ]);

            DB::statement('CALL ActualizarPensum(?, ?, ?)', [
                $id,
                $request->programa_id,
                $request->anio
            ]);

            return response()->json([
                'mensaje' => 'Pensum actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar el pensum',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un pensum
     */
    public function eliminarPensum($id)
    {
        try {
            DB::statement('CALL EliminarPensum(?)', [$id]);

            return response()->json([
                'mensaje' => 'Pensum eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar el pensum',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
