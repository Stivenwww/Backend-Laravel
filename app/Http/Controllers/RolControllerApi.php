<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador API para gestionar roles de usuarios
 * Implementa operaciones CRUD utilizando procedimientos almacenados
 */
class RolControllerApi extends Controller
{
    /**
     * Obtiene todos los roles de la base de datos
     *
     * @return \Illuminate\Http\JsonResponse Lista de roles en formato JSON
     */
    public function traerRoles()
    {
        try {
            // Ejecuta el procedimiento almacenado que devuelve todos los roles
            $roles = DB::select('CALL ObtenerRoles()');
            return response()->json($roles);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al obtener los roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene un rol específico por su ID
     *
     * @param int $id Identificador del rol
     * @return \Illuminate\Http\JsonResponse Datos del rol o mensaje de error
     */
    public function llevarRol($id)
    {
        try {
            // Ejecuta el procedimiento almacenado con el ID como parámetro
            $rol = DB::select('CALL ObtenerRolPorId(?)', [$id]);

            // Verifica si se encontraron resultados
            if (!empty($rol)) {
                return response()->json([
                    'mensaje' => 'Rol encontrado',
                    'datos' => $rol[0] // Retorna solo el primer registro
                ], 200);
            } else {
                // Si no hay resultados, devuelve un 404
                return response()->json([
                    'mensaje' => 'Rol no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al obtener el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea un nuevo rol en la base de datos
     *
     * @param \Illuminate\Http\Request $request Datos del nuevo rol
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function insertarRol(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'nombre' => 'required|string|max:50',
            ]);

            // Ejecuta el procedimiento almacenado con el parámetro del request
            // Nota: Se usa DB::insert pero el comportamiento es el mismo que DB::statement
            DB::insert('CALL InsertarRol(?)', [
                $request->nombre
            ]);

            // Respuesta de éxito con código 201 (Created)
            return response()->json([
                'mensaje' => 'Rol insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al insertar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza los datos de un rol existente
     *
     * @param \Illuminate\Http\Request $request Nuevos datos del rol
     * @param int $id Identificador del rol a actualizar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function actualizarRol(Request $request, $id)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'nombre' => 'required|string|max:50',
            ]);

            // Ejecuta el procedimiento almacenado con el ID y los parámetros del request
            // Nota: Se usa DB::update pero el comportamiento es el mismo que DB::statement
            DB::update('CALL ActualizarRol(?, ?)', [
                $id,
                $request->nombre,
            ]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'Rol actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al actualizar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un rol de la base de datos
     *
     * @param int $id Identificador del rol a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function eliminarRol($id)
    {
        try {
            // Ejecuta el procedimiento almacenado para eliminar el rol
            // Nota: Se usa DB::delete pero el comportamiento es el mismo que DB::statement
            DB::delete('CALL EliminarRol(?)', [$id]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'Rol eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al eliminar el rol',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
