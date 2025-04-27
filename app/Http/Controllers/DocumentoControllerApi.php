<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentoControllerApi extends Controller
{
    // Método para obtener todos los documentos
    public function traerDocumentos()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todos los documentos
            $documentos = DB::select('CALL ObtenerDocumentos()');
            return response()->json($documentos);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener los documentos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener un documento por ID
    public function llevarDocumento($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener un documento por ID
            $documento = DB::select('CALL ObtenerDocumentoPorId(?)', [$id]);

            if (!empty($documento)) {
                return response()->json([
                    'mensaje' => 'Documento encontrado',
                    'datos' => $documento[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Documento no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar un nuevo documento
    public function insertarDocumento(Request $request)
    {
        try {
            // Llamada al procedimiento almacenado para insertar un nuevo documento
            $request->validate([
                'solicitud_id' => 'required|integer',
                'usuario_id' => 'required|integer',
                'tipo' => 'required|string',
                'ruta' => 'required|string|max:255',
            ]);
            DB::statement('CALL InsertarDocumento(?, ?, ?, ?)', [
                $request->solicitud_id,
                $request->usuario_id,
                $request->tipo,
                $request->ruta
            ]);

            return response()->json([
                'mensaje' => 'Documento insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar un documento
    public function actualizarDocumento(Request $request, $id)
    {
        try {
            $request->validate([
                'solicitud_id' => 'sometimes|required|integer',
                'usuario_id' => 'sometimes|required|integer',
                'tipo' => 'sometimes|required|string',
                'ruta' => 'sometimes|required|string|max:255',
            ]);
            // Llamada al procedimiento almacenado para actualizar un documento
            DB::statement('CALL ActualizarDocumento(?, ?, ?, ?, ?)', [
                $id,
                $request->solicitud_id,
                $request->usuario_id,
                $request->tipo,
                $request->ruta
            ]);

            return response()->json([
                'mensaje' => 'Documento actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar un documento
    public function eliminarDocumento($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar un documento
            DB::statement('CALL EliminarDocumento(?)', [$id]);

            return response()->json([
                'mensaje' => 'Documento eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al eliminar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
