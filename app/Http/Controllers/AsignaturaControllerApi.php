<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\ContenidoProgramatico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignaturaControllerApi extends Controller
{
    /**
     * Método para obtener todas las asignaturas.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function traerAsignaturas()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todas las asignaturas
            $asignaturas = DB::select('CALL ObtenerAsignaturas()');
            return response()->json($asignaturas);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON y código 500 (Error del servidor)
            return response()->json([
                'mensaje' => 'Error al obtener las asignaturas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para obtener una asignatura específica por su ID.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @param int $id ID de la asignatura a buscar
     * @return \Illuminate\Http\JsonResponse
     */
    public function llevarAsignatura($id)
    {
        try {
            // Llamada al procedimiento almacenado con parámetro ID
            $asignatura = DB::select('CALL ObtenerAsignaturaPorId(?)', [$id]);

            // Verifica si se encontró la asignatura
            if (!empty($asignatura)) {
                return response()->json([
                    'mensaje' => 'Asignatura encontrada',
                    'datos' => $asignatura[0] // Accede al primer resultado del array
                ], 200);
            } else {
                // Respuesta si no se encuentra la asignatura, código 404 (No encontrado)
                return response()->json([
                    'mensaje' => 'Asignatura no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al obtener la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para insertar una nueva asignatura.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @param Request $request Datos de la asignatura a insertar
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertarAsignatura(Request $request)
    {
        try {
            // Llamada al procedimiento almacenado con múltiples parámetros
            DB::statement('CALL InsertarAsignatura(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $request->programa_id,
                $request->nombre,
                $request->tipo,
                $request->codigo_asignatura,
                $request->creditos,
                $request->semestre,
                $request->horas_sena,
                $request->tiempo_presencial,
                $request->tiempo_independiente,
                $request->horas_totales_semanales,
                $request->modalidad,
                $request->metodologia
            ]);

            // Respuesta exitosa, código 201 (Creado)
            return response()->json([
                'mensaje' => 'Asignatura insertada correctamente'
            ], 201);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al insertar la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para actualizar una asignatura existente.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @param Request $request Datos actualizados de la asignatura
     * @param int $id ID de la asignatura a actualizar
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarAsignatura(Request $request, $id)
    {
        try {
            // Llamada al procedimiento almacenado con ID y demás parámetros
            DB::statement('CALL ActualizarAsignatura(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $request->programa_id,
                $request->nombre,
                $request->tipo,
                $request->codigo_asignatura,
                $request->creditos,
                $request->semestre,
                $request->horas_sena,
                $request->tiempo_presencial,
                $request->tiempo_independiente,
                $request->horas_totales_semanales,
                $request->modalidad,
                $request->metodologia
            ]);

            // Respuesta exitosa, código 200 (OK)
            return response()->json([
                'mensaje' => 'Asignatura actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al actualizar la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método para eliminar una asignatura.
     * Utiliza un procedimiento almacenado en la base de datos.
     *
     * @param int $id ID de la asignatura a eliminar
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarAsignatura($id)
    {
        try {
            // Llamada al procedimiento almacenado para eliminar
            DB::statement('CALL EliminarAsignatura(?)', [$id]);

            // Respuesta exitosa, código 200 (OK)
            return response()->json([
                'mensaje' => 'Asignatura eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores con respuesta JSON
            return response()->json([
                'mensaje' => 'Error al eliminar la asignatura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function traerAsignaturasPorPrograma($id_programa)
    {
        try {
            // Consulta Eloquent con relaciones "programa" y "contenidosProgramaticos"
            $asignaturas = Asignatura::where('programa_id', $id_programa)
                ->with(['programa.facultad', 'contenidosProgramaticos']) // relación anidada
                ->get();


            // Transforma la colección
            $asignaturasConPrograma = $asignaturas->map(function ($asignatura) {
                // Datos base de la asignatura y programa
                $datosAsignatura = [
                    'nombre_programa' => $asignatura->programa->nombre,
                    'id_asignatura' => $asignatura->id_asignatura,
                    'programa_id' => $asignatura->programa_id,
                    'facultad' => $asignatura->programa->facultad->nombre ?? null,
                    'nombre' => $asignatura->nombre,
                    'tipo' => $asignatura->tipo,
                    'codigo_asignatura' => $asignatura->codigo_asignatura,
                    'creditos' => $asignatura->creditos,
                    'semestre' => $asignatura->semestre,
                    'horas_sena' => $asignatura->horas_sena,
                    'tiempo_presencial' => $asignatura->tiempo_presencial,
                    'tiempo_independiente' => $asignatura->tiempo_independiente,
                    'horas_totales_semanales' => $asignatura->horas_totales_semanales,
                    'modalidad' => $asignatura->modalidad,
                    'metodologia' => $asignatura->metodologia,
                    'created_at' => $asignatura->created_at,
                    'updated_at' => $asignatura->updated_at,
                ];

                // Agregar contenidos programáticos como array
                $datosAsignatura['contenidos_programaticos'] = [];

                // Si hay contenidos programáticos, los agregamos al array
                if ($asignatura->contenidosProgramaticos && count($asignatura->contenidosProgramaticos) > 0) {
                    foreach ($asignatura->contenidosProgramaticos as $contenido) {
                        $datosAsignatura['contenidos_programaticos'][] = [
                            'id' => $contenido->id_contenido,  // Usar id_contenido en lugar de id
                            'tema' => $contenido->tema,
                            'resultados_aprendizaje' => $contenido->resultados_aprendizaje,
                            'descripcion' => $contenido->descripcion
                        ];
                    }
                }

                return $datosAsignatura;
            });

            // Respuesta exitosa
            return response()->json([
                'data' => $asignaturasConPrograma,
                'message' => 'Asignaturas del programa recuperadas correctamente',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'message' => 'Error al recuperar las asignaturas: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    public function llevarAsignaturaPorPrograma($id_programa, $id_asignatura)
    {
        try {
            // Consulta Eloquent
            $asignatura = Asignatura::where('programa_id', $id_programa)
                ->where('id_asignatura', $id_asignatura)
                ->with(['programa.facultad', 'contenidosProgramaticos'])
                ->first();


            // Verifica si se encontró la asignatura
            if (!$asignatura) {
                return response()->json([
                    'message' => 'Asignatura no encontrada en este programa',
                    'success' => false
                ], 404);
            }

            // Preparar datos base
            $datosAsignatura = [
                'nombre_programa' => $asignatura->programa->nombre,
                'id_asignatura' => $asignatura->id_asignatura,
                'programa_id' => $asignatura->programa_id,
                'facultad' => $asignatura->programa->facultad->nombre ?? null,
                'nombre' => $asignatura->nombre,
                'tipo' => $asignatura->tipo,
                'codigo_asignatura' => $asignatura->codigo_asignatura,
                'creditos' => $asignatura->creditos,
                'semestre' => $asignatura->semestre,
                'horas_sena' => $asignatura->horas_sena,
                'tiempo_presencial' => $asignatura->tiempo_presencial,
                'tiempo_independiente' => $asignatura->tiempo_independiente,
                'horas_totales_semanales' => $asignatura->horas_totales_semanales,
                'modalidad' => $asignatura->modalidad,
                'metodologia' => $asignatura->metodologia,
                'created_at' => $asignatura->created_at,
                'updated_at' => $asignatura->updated_at,
            ];

            // Inicializar el array de contenidos programáticos
            $datosAsignatura['contenidos_programaticos'] = [];

            // Si hay contenidos programáticos, los agregamos al array
            if ($asignatura->contenidosProgramaticos && count($asignatura->contenidosProgramaticos) > 0) {
                foreach ($asignatura->contenidosProgramaticos as $contenido) {
                    $datosAsignatura['contenidos_programaticos'][] = [
                        'id' => $contenido->id_contenido,  // Usar id_contenido en lugar de id
                        'tema' => $contenido->tema,
                        'resultados_aprendizaje' => $contenido->resultados_aprendizaje,
                        'descripcion' => $contenido->descripcion
                    ];
                }
            }

            // Respuesta exitosa
            return response()->json([
                'data' => $datosAsignatura,
                'message' => 'Asignatura recuperada correctamente',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json([
                'message' => 'Error al recuperar la asignatura: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
