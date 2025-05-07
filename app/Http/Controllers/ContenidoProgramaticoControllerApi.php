<?php

// Define el espacio de nombres para el controlador
namespace App\Http\Controllers;

// Importación de clases necesarias
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Definición de la clase del controlador API
class ContenidoProgramaticoControllerApi extends Controller
{
    // Método para obtener todos los contenidos programáticos desde un procedimiento almacenado
    public function traerContenidosProgramaticos()
    {
        try {
            // Llama al procedimiento almacenado que retorna todos los contenidos programáticos
            $contenidos = DB::select('CALL ObtenerContenidosProgramaticos()');

            // Retorna los datos como respuesta JSON
            return response()->json($contenidos);
        } catch (\Exception $e) {
            // En caso de error, retorna un mensaje con el error capturado
            return response()->json([
                'mensaje' => 'Error al obtener los contenidos programáticos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener un contenido programático por ID
    public function llevarContenidoProgramatico($id)
    {
        try {
            // Llama al procedimiento almacenado que retorna un contenido programático por su ID
            $contenido = DB::select('CALL ObtenerContenidoProgramaticoPorId(?)', [$id]);

            // Si se encuentra, se retorna el primer resultado
            if (!empty($contenido)) {
                return response()->json([
                    'mensaje' => 'Contenido programático encontrado',
                    'datos' => $contenido[0]
                ], 200);
            } else {
                // Si no se encuentra, se informa con código 404
                return response()->json([
                    'mensaje' => 'Contenido programático no encontrado',
                ], 404);
            }
        } catch (\Exception $e) {
            // En caso de error, se retorna el mensaje de excepción
            return response()->json([
                'mensaje' => 'Error al obtener el contenido programático',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar un nuevo contenido programático
    public function insertarContenidoProgramatico(Request $request)
    {
        try {
            // Se llama al procedimiento almacenado con los datos recibidos del formulario
            DB::statement('CALL InsertarContenidoProgramatico(?, ?, ?, ?)', [
                $request->asignatura_id,
                $request->tema,
                $request->resultados_aprendizaje,
                $request->descripcion
            ]);

            // Respuesta exitosa con código 201 (creado)
            return response()->json([
                'mensaje' => 'Contenido programático insertado correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Retorna el error si ocurre algún fallo
            return response()->json([
                'mensaje' => 'Error al insertar el contenido programático',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar un contenido programático existente
    public function actualizarContenidoProgramatico(Request $request, $id)
    {
        try {
            // Llama al procedimiento almacenado con el ID y nuevos datos
            DB::statement('CALL ActualizarContenidoProgramatico(?, ?, ?, ?, ?)', [
                $id,
                $request->asignatura_id,
                $request->tema,
                $request->resultados_aprendizaje,
                $request->descripcion
            ]);

            // Respuesta de éxito
            return response()->json([
                'mensaje' => 'Contenido programático actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'mensaje' => 'Error al actualizar el contenido programático',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar un contenido programático por ID
    public function eliminarContenidoProgramatico($id)
    {
        try {
            // Llama al procedimiento almacenado que elimina el contenido
            DB::statement('CALL EliminarContenidoProgramatico(?)', [$id]);

            // Mensaje de confirmación
            return response()->json([
                'mensaje' => 'Contenido programático eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Captura y respuesta en caso de error
            return response()->json([
                'mensaje' => 'Error al eliminar el contenido programático',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para obtener contenidos programáticos según una asignatura específica.
     * Este método no usa un procedimiento almacenado sino una consulta directa con Eloquent/Query Builder.
     *
     * @param int $asignatura_id ID de la asignatura
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerContenidosPorAsignatura($asignatura_id)
    {
        try {
            // Se obtienen todos los contenidos cuyo asignatura_id coincida
            $contenidos = DB::table('contenidos_programaticos')
                ->where('asignatura_id', $asignatura_id)
                ->get();

            // Devuelve los contenidos o mensaje si no se encuentra ninguno
            return response()->json([
                'mensaje' => $contenidos->count() > 0
                    ? 'Contenidos programáticos encontrados'
                    : 'No se encontraron contenidos para esta asignatura',
                'datos' => $contenidos
            ], 200);
        } catch (\Exception $e) {
            // Error al realizar la consulta
            return response()->json([
                'mensaje' => 'Error al obtener los contenidos programáticos de la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
