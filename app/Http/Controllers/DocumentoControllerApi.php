<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentoControllerApi extends Controller
{
    /**
     * Método para obtener todos los documentos.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function traerDocumentos()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todos los documentos
            $documentos = DB::select('CALL ObtenerDocumentos()');
            return response()->json($documentos);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON y código 500 (Error del servidor)
            return response()->json([
                'mensaje' => 'Error al obtener los documentos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para obtener un documento específico por su ID.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @param int $id ID del documento a buscar
     * @return \Illuminate\Http\JsonResponse
     */
    public function llevarDocumento($id)
    {
        try {
            // Llamada al procedimiento almacenado con parámetro ID
            $documento = DB::select('CALL ObtenerDocumentoPorId(?)', [$id]);

            // Verifica si se encontró el documento
            if (!empty($documento)) {
                return response()->json([
                    'mensaje' => 'Documento encontrado',
                    'datos' => $documento[0] // Accede al primer resultado del array
                ], 200);
            } else {
                // Respuesta si no se encuentra el documento, código 404 (No encontrado)
                return response()->json([
                    'mensaje' => 'Documento no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al obtener el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para insertar un nuevo documento.
     * Incluye validación de datos y utiliza un procedimiento almacenado.
     *
     * @param Request $request Datos del documento a insertar
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertarDocumento(Request $request)
    {
        try {
            // Validación de los datos de entrada usando el validador de Laravel
            $request->validate([
                'solicitud_id' => 'required|integer',
                'usuario_id' => 'required|integer',
                'tipo' => 'required|string',
                'ruta' => 'required|string|max:255',
            ]);

            // Llamada al procedimiento almacenado para insertar
            DB::statement('CALL InsertarDocumento(?, ?, ?, ?)', [
                $request->solicitud_id,
                $request->usuario_id,
                $request->tipo,
                $request->ruta
            ]);

            // Respuesta exitosa, código 201 (Creado)
            return response()->json([
                'mensaje' => 'Documento insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al insertar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para actualizar un documento existente.
     * Incluye validación de datos y utiliza un procedimiento almacenado.
     * La validación usa 'sometimes' para permitir actualizaciones parciales.
     *
     * @param Request $request Datos actualizados del documento
     * @param int $id ID del documento a actualizar
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarDocumento(Request $request, $id)
    {
        try {
            // Validación con 'sometimes' que permite actualización parcial de campos
            // Solo valida si el campo está presente en la solicitud
            $request->validate([
                'solicitud_id' => 'sometimes|required|integer',
                'usuario_id' => 'sometimes|required|integer',
                'tipo' => 'sometimes|required|string',
                'ruta' => 'sometimes|required|string|max:255',
            ]);

            // Llamada al procedimiento almacenado para actualizar
            DB::statement('CALL ActualizarDocumento(?, ?, ?, ?, ?)', [
                $id,
                $request->solicitud_id,
                $request->usuario_id,
                $request->tipo,
                $request->ruta
            ]);

            // Respuesta exitosa, código 200 (OK)
            return response()->json([
                'mensaje' => 'Documento actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al actualizar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para eliminar un documento.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @param int $id ID del documento a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarDocumento($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar
            DB::statement('CALL EliminarDocumento(?)', [$id]);

            // Respuesta exitosa, código 200 (OK)
            return response()->json([
                'mensaje' => 'Documento eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al eliminar el documento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para obtener todos los documentos de un usuario específico.
     * Utiliza una consulta directa a la base de datos sin procedimiento almacenado.
     *
     * @param int $usuario_id ID del usuario cuyos documentos se buscan
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerDocumentosPorUsuario($usuario_id)
    {
        try {
            // Consulta directa para obtener documentos por usuario_id
            $documentos = DB::table('documentos')
                ->where('usuario_id', $usuario_id)
                ->get();

            return response()->json([
                'mensaje' => $documentos->count() > 0 ? 'Documentos encontrados' : 'No se encontraron documentos para este usuario',
                'datos' => $documentos
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON y código 500 (Error del servidor)
            return response()->json([
                'mensaje' => 'Error al obtener los documentos del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
