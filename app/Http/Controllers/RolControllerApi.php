<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolControllerApi extends Controller
{
    // Método para obtener todos los roles
    public function traerRoles()
    {
        try {
            // Consulta para obtener todos los roles
            $roles = DB::select('CALL ObtenerRoles()');
            return response()->json($roles);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener los roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener un rol por ID
    public function llevarRol($id)
    {
        try {
            // Consulta para obtener un rol por ID
            $rol = DB::select('CALL ObtenerRolPorId(?)', [$id]);

            if (!empty($rol)) {
                return response()->json([
                    'mensaje' => 'Rol encontrado',
                    'datos' => $rol[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Rol no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar un nuevo rol
    public function insertarRol(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:50',
              ]);
            // Consulta para insertar un nuevo rol
            DB::insert('CALL InsertarRol(?)', [
                $request->nombre
            ]);

            return response()->json([
                'mensaje' => 'Rol insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar un rol
    public function actualizarRol(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:50',
              ]);
            // Consulta para actualizar un rol
            DB::update('CALL ActualizarRol(?, ?)', [
                $id,
                $request->nombre,
            ]);

            return response()->json([
                'mensaje' => 'Rol actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar un rol
    public function eliminarRol($id)
    {
        try {
            // Consulta para eliminar un rol
            DB::delete('CALL EliminarRol(?)', [$id]);

            return response()->json([
                'mensaje' => 'Rol eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
