<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoControllerApi extends Controller
{
    /**
     * Método para obtener todos los departamentos.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function traerDepartamentos()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todos los departamentos
            $departamentos = DB::select('CALL ObtenerDepartamentos()');
            return response()->json($departamentos);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON y código 500 (Error del servidor)
            return response()->json([
                'mensaje' => 'Error al obtener los departamentos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para obtener un departamento específico por su ID.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @param int $id ID del departamento a buscar
     * @return \Illuminate\Http\JsonResponse
     */
    public function llevarDepartamento($id)
    {
        try {
            // Llamada al procedimiento almacenado con parámetro ID
            $departamento = DB::select('CALL ObtenerDepartamentoPorId(?)', [$id]);

            // Verifica si se encontró el departamento
            if (!empty($departamento)) {
                return response()->json([
                    'mensaje' => 'Departamento encontrado',
                    'datos' => $departamento[0] // Accede al primer resultado del array
                ], 200);
            } else {
                // Respuesta si no se encuentra el departamento, código 404 (No encontrado)
                return response()->json([
                    'mensaje' => 'Departamento no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al obtener el departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para insertar un nuevo departamento.
     * Incluye validación de datos y utiliza un procedimiento almacenado.
     *
     * @param Request $request Datos del departamento a insertar
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertarDepartamento(Request $request)
    {
        try {
            // Validación de los datos de entrada usando el validador de Laravel
            $request->validate([
                'nombre'  => 'required|string|max:255',
                'pais_id' => 'required|exists:paises,id_pais', // Verifica que el pais_id exista en la tabla paises
            ]);

            // Llamada al procedimiento almacenado para insertar
            DB::statement('CALL InsertarDepartamento(?, ?)', [
                $request->nombre,
                $request->pais_id
            ]);

            // Respuesta exitosa, código 201 (Creado)
            return response()->json([
                'mensaje' => 'Departamento insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al insertar el departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para actualizar un departamento existente.
     * Incluye validación de datos y utiliza un procedimiento almacenado.
     *
     * @param Request $request Datos actualizados del departamento
     * @param int $id ID del departamento a actualizar
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarDepartamento(Request $request, $id)
    {
        try {
            // Validación de los datos de entrada usando el validador de Laravel
            $request->validate([
                'nombre'  => 'required|string|max:255',
                'pais_id' => 'required|exists:paises,id_pais', // Verifica que el pais_id exista en la tabla paises
            ]);

            // Llamada al procedimiento almacenado para actualizar
            DB::statement('CALL ActualizarDepartamento(?, ?, ?)', [
                $id,
                $request->nombre,
                $request->pais_id
            ]);

            // Respuesta exitosa, código 200 (OK)
            return response()->json([
                'mensaje' => 'Departamento actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al actualizar el departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para eliminar un departamento.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @param int $id ID del departamento a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarDepartamento($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar
            DB::statement('CALL EliminarDepartamento(?)', [$id]);

            // Respuesta exitosa, código 200 (OK)
            return response()->json([
                'mensaje' => 'Departamento eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al eliminar el departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
