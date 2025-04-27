<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramaControllerApi extends Controller
{
    // Método para obtener todos los programas
    public function traerProgramas()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todos los programas
            $programas = DB::select('CALL ObtenerProgramas()');
            return response()->json($programas);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener los programas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener un programa por ID
    public function llevarPrograma($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener un programa por ID
            $programa = DB::select('CALL ObtenerProgramaPorId(?)', [$id]);

            if (!empty($programa)) {
                return response()->json([
                    'mensaje' => 'Programa encontrado',
                    'datos' => $programa[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Programa no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener el programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar un nuevo programa
    public function insertarPrograma(Request $request)
    {
        try {
            $request->validate([
                'institucion_id'  => 'required|integer|exists:instituciones,id_institucion',
                'facultad_id'     => 'nullable|integer|exists:facultades,id_facultad',
                'nombre'          => 'required|string|max:255',
                'codigo_snies'    => 'nullable|string|max:20',
                'tipo_formacion'  => 'required|in:Técnico,Tecnólogo,Profesional',
                'metodologia'     => 'required|in:Presencial,Virtual,Híbrido',
            ]);
            // Llamada al procedimiento almacenado para insertar un nuevo programa
            DB::statement('CALL InsertarPrograma(?, ?, ?, ?, ?, ?)', [
                $request->institucion_id,
                $request->facultad_id,
                $request->nombre,
                $request->codigo_snies,
                $request->tipo_formacion,
                $request->metodologia
            ]);

            return response()->json([
                'mensaje' => 'Programa insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar el programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar un programa
    public function actualizarPrograma(Request $request, $id)
    {
        try {
            $request->validate([
                'institucion_id'  => 'required|integer|exists:instituciones,id_institucion',
                'facultad_id'     => 'nullable|integer|exists:facultades,id_facultad',
                'nombre'          => 'required|string|max:255',
                'codigo_snies'    => 'nullable|string|max:20',
                'tipo_formacion'  => 'required|in:Técnico,Tecnólogo,Profesional',
                'metodologia'     => 'required|in:Presencial,Virtual,Híbrido',
            ]);
            // Llamada al procedimiento almacenado para actualizar un programa
            DB::statement('CALL ActualizarPrograma(?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $request->institucion_id,
                $request->facultad_id,
                $request->nombre,
                $request->codigo_snies,
                $request->tipo_formacion,
                $request->metodologia
            ]);

            return response()->json([
                'mensaje' => 'Programa actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar el programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar un programa
    public function eliminarPrograma($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar un programa
            DB::statement('CALL EliminarPrograma(?)', [$id]);

            return response()->json([
                'mensaje' => 'Programa eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar el programa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
