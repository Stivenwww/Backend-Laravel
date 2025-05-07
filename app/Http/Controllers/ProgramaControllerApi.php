<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controlador API para gestionar programas académicos
 * Implementa operaciones CRUD utilizando procedimientos almacenados
 */
class ProgramaControllerApi extends Controller
{
    /**
     * Obtiene todos los programas académicos de la base de datos
     *
     * @return \Illuminate\Http\JsonResponse Lista de programas en formato JSON
     */
    public function traerProgramas()
    {
        try {
            // Ejecuta el procedimiento almacenado que devuelve todos los programas
            $programas = DB::select('CALL ObtenerProgramas()');
            return response()->json($programas);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al obtener los programas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene un programa académico específico por su ID
     *
     * @param int $id Identificador del programa
     * @return \Illuminate\Http\JsonResponse Datos del programa o mensaje de error
     */
    public function llevarPrograma($id)
    {
        try {
            // Ejecuta el procedimiento almacenado con el ID como parámetro
            $programa = DB::select('CALL ObtenerProgramaPorId(?)', [$id]);

            // Verifica si se encontraron resultados
            if (!empty($programa)) {
                return response()->json([
                    'mensaje' => 'Programa encontrado',
                    'datos' => $programa[0] // Retorna solo el primer registro
                ], 200);
            } else {
                // Si no hay resultados, devuelve un 404
                return response()->json([
                    'mensaje' => 'Programa no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al obtener el programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea un nuevo programa académico en la base de datos
     *
     * @param \Illuminate\Http\Request $request Datos del nuevo programa
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function insertarPrograma(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'institucion_id' => 'required|integer|exists:instituciones,id_institucion',
                'facultad_id' => 'nullable|integer|exists:facultades,id_facultad',
                'nombre' => 'required|string|max:255',
                'codigo_snies' => 'nullable|string|max:20',
                'tipo_formacion' => 'required|in:Técnico,Tecnólogo,Profesional',
                'metodologia' => 'required|in:Presencial,Virtual,Híbrido',
            ]);

            // Ejecuta el procedimiento almacenado con los parámetros del request
            DB::statement('CALL InsertarPrograma(?, ?, ?, ?, ?, ?)', [
                $request->institucion_id,
                $request->facultad_id,
                $request->nombre,
                $request->codigo_snies,
                $request->tipo_formacion,
                $request->metodologia
            ]);

            // Respuesta de éxito con código 201 (Created)
            return response()->json([
                'mensaje' => 'Programa insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al insertar el programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza los datos de un programa académico existente
     *
     * @param \Illuminate\Http\Request $request Nuevos datos del programa
     * @param int $id Identificador del programa a actualizar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function actualizarPrograma(Request $request, $id)
    {
        try {
            // Validación de los datos de entrada
            $request->validate([
                'institucion_id' => 'required|integer|exists:instituciones,id_institucion',
                'facultad_id' => 'nullable|integer|exists:facultades,id_facultad',
                'nombre' => 'required|string|max:255',
                'codigo_snies' => 'nullable|string|max:20|unique:programas,codigo_snies,' . $id . ',id_programa',
                'tipo_formacion' => 'required|in:Técnico,Tecnólogo,Profesional',
                'metodologia' => 'required|in:Presencial,Virtual,Híbrido',
            ]);

            // Ejecuta el procedimiento almacenado con el ID y los parámetros del request
            DB::statement('CALL ActualizarPrograma(?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $request->institucion_id,
                $request->facultad_id,
                $request->nombre,
                $request->codigo_snies,
                $request->tipo_formacion,
                $request->metodologia
            ]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'Programa actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al actualizar el programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un programa académico de la base de datos
     *
     * @param int $id Identificador del programa a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación o error
     */
    public function eliminarPrograma($id)
    {
        try {
            // Ejecuta el procedimiento almacenado para eliminar el programa
            DB::statement('CALL EliminarPrograma(?)', [$id]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'Programa eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta 500
            return response()->json([
                'mensaje' => 'Error al eliminar el programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
