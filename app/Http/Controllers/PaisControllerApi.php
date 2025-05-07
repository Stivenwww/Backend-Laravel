<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador API para gestionar países
 * Proporciona operaciones CRUD utilizando procedimientos almacenados
 */
class PaisControllerApi extends Controller
{
    /**
     * Obtiene todos los países de la base de datos
     *
     * @return \Illuminate\Http\JsonResponse Lista de países en formato JSON
     */
    public function traerPaises()
    {
        try {
            // Ejecuta el procedimiento almacenado que devuelve todos los países
            $paises = DB::select('CALL ObtenerPaises()');
            return response()->json($paises);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al obtener los países',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene un país específico por su ID
     *
     * @param int $id Identificador del país
     * @return \Illuminate\Http\JsonResponse Datos del país o mensaje de error
     */
    public function llevarPais($id)
    {
        try {
            // Ejecuta el procedimiento almacenado con el ID como parámetro
            $pais = DB::select('CALL ObtenerPaisPorId(?)', [$id]);

            // Verifica si se encontraron resultados
            if (!empty($pais)) {
                return response()->json([
                    'mensaje' => 'País encontrado',
                    'datos' => $pais[0] // Retorna solo el primer registro
                ], 200);
            } else {
                // Si no hay resultados, devuelve un 404
                return response()->json([
                    'mensaje' => 'País no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al obtener el país',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea un nuevo país en la base de datos
     *
     * @param \Illuminate\Http\Request $request Datos del nuevo país
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function insertarPais(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'nombre' => 'required|string|max:100',
            ]);

            // Ejecuta el procedimiento almacenado con el parámetro del request
            DB::statement('CALL InsertarPais(?)', [
                $request->nombre
            ]);

            // Respuesta de éxito con código 201 (Created)
            return response()->json([
                'mensaje' => 'País insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al insertar el país',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza los datos de un país existente
     *
     * @param \Illuminate\Http\Request $request Nuevos datos del país
     * @param int $id Identificador del país a actualizar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function actualizarPais(Request $request, $id)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'nombre' => 'required|string|max:100',
            ]);

            // Ejecuta el procedimiento almacenado con el ID y el parámetro del request
            DB::statement('CALL ActualizarPais(?, ?)', [
                $id,
                $request->nombre
            ]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'País actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al actualizar el país',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un país de la base de datos
     *
     * @param int $id Identificador del país a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function eliminarPais($id)
    {
        try {
            // Ejecuta el procedimiento almacenado para eliminar el país
            DB::statement('CALL EliminarPais(?)', [$id]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'País eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al eliminar el país',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
