<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudAsignatura;
use App\Models\Asignatura;
use App\Models\Programa;
use App\Models\Facultad;
use App\Models\Institucion;
use App\Models\Solicitud;

/**
* Controlador API para gestionar la relación entre solicitudes y asignaturas
* Maneja operaciones CRUD para solicitudes de asignaturas usando procedimientos almacenados
*/
class SolicitudAsignaturaControllerApi extends Controller
{
   /**
    * Obtiene todas las solicitudes de asignaturas con su información detallada
    *
    * @return \Illuminate\Http\JsonResponse Lista de solicitudes de asignaturas formateadas
    */
   public function traerSolicitudAsignaturas()
   {
       try {
           // Llamada al procedimiento almacenado que devuelve todas las solicitudes
           $solicitudes = DB::select('CALL ObtenerSolicitudAsignaturas()');
           $resultados = [];

           // Itera cada solicitud para dar formato adecuado a los datos
           foreach ($solicitudes as $solicitud) {
               $resultados[] = $this->formatearDatosSolicitud($solicitud);
           }

           return response()->json($resultados);
       } catch (\Exception $e) {
           // Manejo detallado de errores con información de debug
           return response()->json([
               'mensaje' => 'Error al obtener las solicitudes de asignaturas',
               'error' => $e->getMessage(),
               'linea' => $e->getLine(),
               'archivo' => $e->getFile()
           ], 500);
       }
   }

   /**
    * Obtiene una solicitud de asignatura específica por su ID
    *
    * @param int $id Identificador de la solicitud de asignatura
    * @return \Illuminate\Http\JsonResponse Datos de la solicitud o mensaje de error
    */
   public function llevarSolicitudAsignatura($id)
   {
       try {
           // Ejecuta el procedimiento almacenado con el ID como parámetro
           $solicitud = DB::select('CALL ObtenerSolicitudAsignaturaPorId(?)', [$id]);

           // Verifica si se encontraron resultados
           if (!empty($solicitud)) {
               // Formatea los datos obtenidos
               $datosFormateados = $this->formatearDatosSolicitud($solicitud[0]);

               return response()->json([
                   'mensaje' => 'Solicitud de asignatura encontrada',
                   'datos' => $datosFormateados
               ], 200);
           } else {
               // Si no hay resultados, devuelve un 404
               return response()->json([
                   'mensaje' => 'Solicitud de asignatura no encontrada',
               ], 404);
           }
       } catch (\Exception $e) {
           // Manejo detallado de errores
           return response()->json([
               'mensaje' => 'Error al obtener la solicitud de asignatura',
               'error' => $e->getMessage(),
               'linea' => $e->getLine(),
               'archivo' => $e->getFile()
           ], 500);
       }
   }

   /**
    * Crea una nueva asociación entre solicitud y asignaturas
    *
    * @param \Illuminate\Http\Request $request Datos de la nueva solicitud con asignaturas
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function insertarSolicitudAsignatura(Request $request)
   {
       try {
           // Validación de estructura de datos esperada
           $request->validate([
               'solicitud_id' => 'required|integer',
               'asignaturas' => 'required|array', // Array de asignaturas a asociar
               'asignaturas.*.asignatura_id' => 'required|integer',
               'asignaturas.*.nota_origen' => 'nullable|numeric',
               'asignaturas.*.horas_sena' => 'nullable|integer',
           ]);

           // Serializa array de asignaturas a JSON para almacenamiento
           $asignaturasJson = json_encode($request->asignaturas);

           // Ejecuta el procedimiento almacenado con los parámetros
           DB::statement('CALL InsertarSolicitudAsignatura(?, ?)', [
               $request->solicitud_id,
               $asignaturasJson
           ]);

           // Respuesta de éxito con código 201 (Created)
           return response()->json([
               'mensaje' => 'Solicitud de asignatura insertada correctamente'
           ], 201);
       } catch (\Exception $e) {
           // Manejo detallado de errores
           return response()->json([
               'mensaje' => 'Error al insertar la solicitud de asignatura',
               'error' => $e->getMessage(),
               'linea' => $e->getLine(),
               'archivo' => $e->getFile()
           ], 500);
       }
   }

   /**
    * Actualiza los datos de una solicitud de asignatura existente
    *
    * @param \Illuminate\Http\Request $request Nuevos datos de la solicitud
    * @param int $id Identificador de la solicitud a actualizar
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function actualizarSolicitudAsignatura(Request $request, $id)
   {
       try {
           // Validación de estructura de datos
           $request->validate([
               'solicitud_id' => 'required|integer',
               'asignaturas' => 'required|array',
               'asignaturas.*.asignatura_id' => 'required|integer',
               'asignaturas.*.nota_origen' => 'nullable|numeric',
               'asignaturas.*.horas_sena' => 'nullable|integer',
           ]);

           // Serializa el array a JSON para almacenamiento
           $asignaturasJson = json_encode($request->asignaturas);

           // Ejecuta el procedimiento almacenado para actualizar
           DB::statement('CALL ActualizarSolicitudAsignatura(?, ?, ?)', [
               $id,
               $request->solicitud_id,
               $asignaturasJson
           ]);

           // Respuesta de éxito
           return response()->json([
               'mensaje' => 'Solicitud de asignatura actualizada correctamente'
           ], 200);
       } catch (\Exception $e) {
           // Manejo detallado de errores
           return response()->json([
               'mensaje' => 'Error al actualizar la solicitud de asignatura',
               'error' => $e->getMessage(),
               'linea' => $e->getLine(),
               'archivo' => $e->getFile()
           ], 500);
       }
   }

   /**
    * Elimina una solicitud de asignatura de la base de datos
    *
    * @param int $id Identificador de la solicitud a eliminar
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function eliminarSolicitudAsignatura($id)
   {
       try {
           // Ejecuta el procedimiento almacenado para eliminar
           DB::statement('CALL EliminarSolicitudAsignatura(?)', [$id]);

           // Respuesta de éxito
           return response()->json([
               'mensaje' => 'Solicitud de asignatura eliminada correctamente'
           ], 200);
       } catch (\Exception $e) {
           // Manejo detallado de errores
           return response()->json([
               'mensaje' => 'Error al eliminar la solicitud de asignatura',
               'error' => $e->getMessage(),
               'linea' => $e->getLine(),
               'archivo' => $e->getFile()
           ], 500);
       }
   }

   
   /**
    * Da formato a los datos crudos de solicitud para presentación en API
    * Incluye detalles de asignaturas asociadas y su jerarquía académica
    *
    * @param object $solicitud Datos crudos de la solicitud
    * @return array Datos formateados con estructura jerárquica
    */
   private function formatearDatosSolicitud($solicitud)
   {
       // Prepara estructura base de datos de solicitud
       $resultado = [
           'id_solicitud_asignatura' => $solicitud->id_solicitud_asignatura ?? null,
           'solicitud_id' => $solicitud->solicitud_id ?? null,
           'numero_radicado' => $solicitud->numero_radicado ?? null,
           'estudiante' => $solicitud->estudiante ?? null,
           'institucion' => $solicitud->institucion ?? null,
           'created_at' => $solicitud->created_at ?? null,
           'updated_at' => $solicitud->updated_at ?? null,
           'asignaturas' => []
       ];

       // Deserializa las asignaturas desde JSON
       $asignaturasArray = json_decode($solicitud->asignaturas ?? '[]', true);

       // Valida que sea un array
       if (!is_array($asignaturasArray)) {
           return $resultado;
       }

       // Procesa cada asignatura en el array
       foreach ($asignaturasArray as $asignaturaItem) {
           $asignaturaId = $asignaturaItem['asignatura_id'] ?? 0;

           // Salta asignaturas sin ID válido
           if (empty($asignaturaId)) {
               continue;
           }

           // Obtiene información completa de la asignatura
           $infoAsignatura = $this->obtenerInfoAsignatura($asignaturaId);

           // Añade información específica de esta relación solicitud-asignatura
           if (isset($asignaturaItem['nota_origen']) && $asignaturaItem['nota_origen'] !== null) {
               $infoAsignatura['nota_origen'] = $asignaturaItem['nota_origen'];
           } else {
               $infoAsignatura['nota_origen'] = null;
           }

           if (isset($asignaturaItem['horas_sena']) && $asignaturaItem['horas_sena'] !== null) {
               $infoAsignatura['horas_sena'] = $asignaturaItem['horas_sena'];
           } else {
               $infoAsignatura['horas_sena'] = null;
           }

           // Agrega la asignatura al array resultante
           $resultado['asignaturas'][] = $infoAsignatura;
       }

       return $resultado;
   }

   /**
    * Recupera información detallada de una asignatura y su jerarquía académica
    *
    * @param int $asignaturaId Identificador de la asignatura
    * @return array Datos completos de la asignatura con su contexto académico
    */
   private function obtenerInfoAsignatura($asignaturaId)
   {
       // Estructura predeterminada con valores por defecto
       $info = [
           'id' => $asignaturaId,
           'nombre' => 'No disponible',
           'codigo' => 'N/A',
           'semestre' => 0,
           'programa' => 'No disponible',
           'facultad' => 'No disponible',
           'institucion' => 'No disponible',
           'nota_origen' => null,
           'horas_sena' => null
       ];

       try {
           // Consulta el modelo de Asignatura
           $asignatura = Asignatura::find($asignaturaId);

           if (!$asignatura) {
               return $info;
           }

           // Asigna datos básicos de la asignatura
           $info['nombre'] = $asignatura->nombre ?? 'No disponible';
           $info['codigo'] = $asignatura->codigo_asignatura ?? 'N/A';
           $info['semestre'] = $asignatura->semestre ?? 0;

           // Obtiene jerarquía académica completa
           try {
               if ($asignatura->programa_id) {
                   $programa = Programa::find($asignatura->programa_id);
                   if ($programa) {
                       $info['programa'] = $programa->nombre ?? 'No disponible';

                       // Obtiene facultad asociada al programa
                       if ($programa->facultad_id) {
                           $facultad = Facultad::find($programa->facultad_id);
                           if ($facultad) {
                               $info['facultad'] = $facultad->nombre ?? 'No disponible';
                           }
                       }

                       // Obtiene institución asociada al programa
                       if ($programa->institucion_id) {
                           $institucion = Institucion::find($programa->institucion_id);
                           if ($institucion) {
                               $info['institucion'] = $institucion->nombre ?? 'No disponible';
                           }
                       }
                   }
               }
           } catch (\Exception $e) {
               // Silencia errores en consultas de jerarquía
           }
       } catch (\Exception $e) {
           // Silencia errores en consulta principal
       }

       return $info;
   }
}
