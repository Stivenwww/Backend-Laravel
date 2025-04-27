<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoControllerApi extends Controller
{
    // Método para obtener todos los departamentos
    public function traerDepartamentos()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todos los departamentos
            $departamentos = DB::select('CALL ObtenerDepartamentos()');
            return response()->json($departamentos);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener los departamentos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener un departamento por ID
    public function llevarDepartamento($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener un departamento por ID
            $departamento = DB::select('CALL ObtenerDepartamentoPorId(?)', [$id]);

            if (!empty($departamento)) {
                return response()->json([
                    'mensaje' => 'Departamento encontrado',
                    'datos' => $departamento[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Departamento no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener el departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar un nuevo departamento
    public function insertarDepartamento(Request $request)
    {
        try {
            // Llamada al procedimiento almacenado para insertar un nuevo departamento

            // Validación de parámetros
            $request->validate([
                'nombre'  => 'required|string|max:255',
                'pais_id' => 'required|exists:paises,id_pais',
              ]);


            DB::statement('CALL InsertarDepartamento(?, ?)', [
                $request->nombre,
                $request->pais_id
            ]);

            return response()->json([
                'mensaje' => 'Departamento insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar el departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar un departamento
    public function actualizarDepartamento(Request $request, $id)
    {
        try {

            // Llamada al procedimiento almacenado para actualizar un departamento

            // Validación de parámetros
            $request->validate([
                'nombre'  => 'required|string|max:255',
                'pais_id' => 'required|exists:paises,id_pais',
              ]);

            DB::statement('CALL ActualizarDepartamento(?, ?, ?)', [
                $id,
                $request->nombre,
                $request->pais_id
            ]);

            return response()->json([
                'mensaje' => 'Departamento actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar el departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar un departamento
    public function eliminarDepartamento($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar un departamento
            DB::statement('CALL EliminarDepartamento(?)', [$id]);

            return response()->json([
                'mensaje' => 'Departamento eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar el departamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
