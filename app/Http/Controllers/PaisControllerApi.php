<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaisControllerApi extends Controller
{
    // Método para obtener todos los países
    public function traerPaises()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todos los países
            $paises = DB::select('CALL ObtenerPaises()');
            return response()->json($paises);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener los países',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener un país por ID
    public function llevarPais($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener un país por ID
            $pais = DB::select('CALL ObtenerPaisPorId(?)', [$id]);

            if (!empty($pais)) {
                return response()->json([
                    'mensaje' => 'País encontrado',
                    'datos' => $pais[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'País no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener el país',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar un nuevo país
    public function insertarPais(Request $request)
    {
        try {
            // Llamada al procedimiento almacenado para insertar un nuevo país

            // Hacemos validaciones de entrada.
            $request->validate([
                'nombre' => 'required|string|max:100',
            ]);

            DB::statement('CALL InsertarPais(?)', [
                $request->nombre
            ]);

            return response()->json([
                'mensaje' => 'País insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar el país',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar un país
    public function actualizarPais(Request $request, $id)
    {
        try {
             // Hacemos validaciones de entrada.
             $request->validate([
                'nombre' => 'required|string|max:100',
            ]);

            // Llamada al procedimiento almacenado para actualizar un país
            DB::statement('CALL ActualizarPais(?, ?)', [
                $id,
                $request->nombre
            ]);

            return response()->json([
                'mensaje' => 'País actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar el país',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar un país
    public function eliminarPais($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar un país
            DB::statement('CALL EliminarPais(?)', [$id]);

            return response()->json([
                'mensaje' => 'País eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar el país',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
