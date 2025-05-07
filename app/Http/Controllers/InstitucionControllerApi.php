<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador API para gestionar instituciones educativas
 * Maneja operaciones CRUD utilizando procedimientos almacenados
 */
class InstitucionControllerApi extends Controller
{
    /**
     * Obtiene todas las instituciones de la base de datos
     *
     * @return \Illuminate\Http\JsonResponse Lista de instituciones en formato JSON
     */
    public function traerInstituciones()
    {
        try {
            // Ejecuta el procedimiento almacenado que devuelve todas las instituciones
            $instituciones = DB::select('CALL ObtenerInstituciones()');
            return response()->json($instituciones);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al obtener las instituciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene una institución específica por su ID
     *
     * @param int $id Identificador de la institución
     * @return \Illuminate\Http\JsonResponse Datos de la institución o mensaje de error
     */
    public function llevarInstitucion($id)
    {
        try {
            // Ejecuta el procedimiento almacenado con el ID como parámetro
            $institucion = DB::select('CALL ObtenerInstitucionPorId(?)', [$id]);

            // Verifica si se encontraron resultados
            if (!empty($institucion)) {
                return response()->json([
                    'mensaje' => 'Institución encontrada',
                    'datos' => $institucion[0] // Retorna solo el primer registro
                ], 200);
            } else {
                // Si no hay resultados, devuelve un 404
                return response()->json([
                    'mensaje' => 'Institución no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al obtener la institución',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea una nueva institución en la base de datos
     *
     * @param \Illuminate\Http\Request $request Datos de la nueva institución
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function insertarInstitucion(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'nombre'       => 'required|string|max:255',
                'codigo_ies'   => 'nullable|string|max:20|unique:instituciones,codigo_ies',
                'municipio_id' => 'nullable|exists:municipios,id_municipio',
                'tipo'         => 'required|in:Universitaria,SENA,Mixta',
            ]);

            // Ejecuta el procedimiento almacenado con los parámetros del request
            DB::statement('CALL InsertarInstitucion(?, ?, ?, ?)', [
                $request->nombre,
                $request->codigo_ies,
                $request->municipio_id,
                $request->tipo,
            ]);

            // Respuesta de éxito con código 201 (Created)
            return response()->json([
                'mensaje' => 'Institución insertada correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al insertar la institución',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza los datos de una institución existente
     *
     * @param \Illuminate\Http\Request $request Nuevos datos de la institución
     * @param int $id Identificador de la institución a actualizar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function actualizarInstitucion(Request $request, $id)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'nombre'       => 'required|string|max:255',
                'codigo_ies'   => 'nullable|string|max:20|',
                'municipio_id' => 'nullable|exists:municipios,id_municipio',
                'tipo'         => 'required|in:Universitaria,SENA,Mixta',
            ]);

            // Ejecuta el procedimiento almacenado con el ID y los parámetros del request
            DB::statement('CALL ActualizarInstitucion(?, ?, ?, ?, ?)', [
                $id,
                $request->nombre,
                $request->codigo_ies,
                $request->municipio_id,
                $request->tipo,
            ]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'Institución actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al actualizar la institución',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina una institución de la base de datos
     *
     * @param int $id Identificador de la institución a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function eliminarInstitucion($id)
    {
        try {
            // Ejecuta el procedimiento almacenado para eliminar la institución
            DB::statement('CALL EliminarInstitucion(?)', [$id]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'Institución eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al eliminar la institución',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
