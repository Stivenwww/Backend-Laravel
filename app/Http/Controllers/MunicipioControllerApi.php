<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador API para gestionar municipios
 * Implementa operaciones CRUD utilizando procedimientos almacenados
 */
class MunicipioControllerApi extends Controller
{
    /**
     * Obtiene todos los municipios de la base de datos
     *
     * @return \Illuminate\Http\JsonResponse Lista de municipios en formato JSON
     */
    public function traerMunicipios()
    {
        $municipios = DB::select('CALL ObtenerMunicipios()');
        return response()->json($municipios);
    }

    /**
     * Obtiene un municipio específico por su ID
     *
     * @param int $id Identificador del municipio
     * @return \Illuminate\Http\JsonResponse Datos del municipio o mensaje de error
     */
    public function llevarMunicipio($id)
    {
        $municipio = DB::select('CALL ObtenerMunicipioPorId(?)', [$id]);

        // Verifica si se encontró el municipio
        if (!empty($municipio)) {
            return response()->json([
                'mensaje' => 'Municipio encontrado',
                'datos' => $municipio[0] // Retorna solo el primer registro
            ], 200);
        } else {
            // Si no hay resultados, devuelve un 404
            return response()->json([
                'mensaje' => 'Municipio no encontrado',
            ], 404);
        }
    }

    /**
     * Crea un nuevo municipio en la base de datos
     *
     * @param \Illuminate\Http\Request $request Datos del nuevo municipio
     * @return \Illuminate\Http\JsonResponse Confirmación de la operación
     */
    public function insertarMunicipio(Request $request)
    {
        // Validación de los datos de entrada
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'departamento_id'  => 'required|integer|exists:departamentos,id_departamento',
        ]);

        // Ejecuta el procedimiento almacenado con los parámetros del request
        DB::statement('CALL InsertarMunicipio(?, ?)', [
            $request->nombre,
            $request->departamento_id
        ]);

        // Respuesta de éxito con código 201 (Created)
        return response()->json(['mensaje' => 'Municipio insertado correctamente'], 201);
    }

    /**
     * Actualiza los datos de un municipio existente
     *
     * @param \Illuminate\Http\Request $request Nuevos datos del municipio
     * @param int $id Identificador del municipio a actualizar
     * @return \Illuminate\Http\JsonResponse Confirmación de la operación
     */
    public function actualizarMunicipio(Request $request, $id)
    {
        // Validación de los datos de entrada
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'departamento_id'  => 'required|integer|exists:departamentos,id_departamento',
        ]);

        // Ejecuta el procedimiento almacenado con el ID y los parámetros del request
        DB::statement('CALL ActualizarMunicipio(?, ?, ?)', [
            $id,
            $request->nombre,
            $request->departamento_id
        ]);

        // Respuesta de éxito
        return response()->json(['mensaje' => 'Municipio actualizado correctamente'], 200);
    }

    /**
     * Elimina un municipio de la base de datos
     *
     * @param int $id Identificador del municipio a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de la operación
     */
    public function eliminarMunicipio($id)
    {
        // Ejecuta el procedimiento almacenado para eliminar el municipio
        DB::statement('CALL EliminarMunicipio(?)', [$id]);

        // Respuesta de éxito
        return response()->json(['mensaje' => 'Municipio eliminado correctamente'], 200);
    }
}
