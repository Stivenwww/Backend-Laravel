<?php

namespace App\Http\Controllers;

use App\Mail\AspiranteMailable;
use App\Mail\ControlSeguimientoMailable;
use App\Mail\CoordinacionMailable;
use App\Mail\SecretariaMailable;
use App\Mail\VicerrectoriaMailable;
use App\Mail\ViserrectoriaMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
* Controlador API para gestionar solicitudes de homologación
* Maneja operaciones CRUD y envío de notificaciones por correo electrónico
*/
class SolicitudControllerApi extends Controller
{
   /**
    * Controlador para envío de notificaciones a secretaría
    *
    * @var NotificacionSecretariaController
    */
   public $notificacionSecretariaController;

   /**
    * Constructor del controlador con inyección de dependencias
    *
    * @param NotificacionSecretariaController $notificacionSecretariaController
    */
   public function __construct(
       NotificacionSecretariaController $notificacionSecretariaController
   ) {
       $this->notificacionSecretariaController = $notificacionSecretariaController;
   }

   /**
    * Obtiene todas las solicitudes de homologación
    *
    * @return \Illuminate\Http\JsonResponse Lista de solicitudes en formato JSON
    */
   public function traerSolicitudes()
   {
       try {
           // Ejecuta el procedimiento almacenado que devuelve todas las solicitudes
           $solicitudes = DB::select('CALL ObtenerSolicitudes()');
           return response()->json($solicitudes);
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al obtener las solicitudes',
               'error' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Obtiene una solicitud específica por su ID
    *
    * @param int $id Identificador de la solicitud
    * @return \Illuminate\Http\JsonResponse Datos de la solicitud o mensaje de error
    */
   public function llevarSolicitud($id)
   {
       try {
           // Ejecuta el procedimiento almacenado con el ID como parámetro
           $solicitud = DB::select('CALL ObtenerSolicitudPorId(?)', [$id]);

           // Verifica si se encontraron resultados
           if (!empty($solicitud)) {
               return response()->json([
                   'mensaje' => 'Solicitud encontrada',
                   'datos' => $solicitud[0] // Devuelve solo el primer registro
               ], 200);
           } else {
               // Si no hay resultados, devuelve un 404
               return response()->json([
                   'mensaje' => 'Solicitud no encontrada',
               ], 404);
           }
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al obtener la solicitud',
               'error' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Crea una nueva solicitud de homologación y envía notificaciones
    *
    * @param \Illuminate\Http\Request $request Datos de la nueva solicitud
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function insertarSolicitud(Request $request)
   {
       try {
           // Usa el modelo para crear solicitud con generación automática de número de radicado
           $solicitud = new Solicitud();
           $solicitud->usuario_id = $request->usuario_id;
           $solicitud->programa_destino_id = $request->programa_destino_id;
           $solicitud->finalizo_estudios = $request->finalizo_estudios;
           $solicitud->fecha_finalizacion_estudios = $request->fecha_finalizacion_estudios;
           $solicitud->fecha_ultimo_semestre_cursado = $request->fecha_ultimo_semestre_cursado;
           $solicitud->estado = $request->estado ?? 'Radicado'; // Valor predeterminado
           $solicitud->ruta_pdf_resolucion = $request->ruta_pdf_resolucion;
           $solicitud->save();

           // Registra información detallada en el log
           Log::info('Solicitud insertada correctamente', [
               'usuario_id' => $request->usuario_id,
               'programa_destino_id' => $request->programa_destino_id,
               'finalizo_estudios' => $request->finalizo_estudios,
               'fecha_finalizacion_estudios' => $request->fecha_finalizacion_estudios,
               'fecha_ultimo_semestre_cursado' => $request->fecha_ultimo_semestre_cursado,
               'estado' => $request->estado ?? 'Radicado',
               'ruta_pdf_resolucion' => $request->ruta_pdf_resolucion
           ]);

           // Envía notificaciones por correo electrónico
           $this->enviarCorreo($solicitud->id_solicitud);

           // Respuesta de éxito con código 201 (Created) e información de la solicitud creada
           return response()->json([
               'mensaje' => 'Solicitud insertada correctamente',
               'numero_radicado' => $solicitud->numero_radicado,
               'id_solicitud' => $solicitud->id_solicitud
           ], 201);
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al insertar la solicitud',
               'error' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Envía correos electrónicos de notificación a múltiples destinatarios
    *
    * @param int $solicitudId Identificador de la solicitud
    * @return bool Resultado de la operación de envío
    */
   public function enviarCorreo($solicitudId)
   {
       try {
           // Obtiene la solicitud con relaciones de usuario y programa
           $solicitud = Solicitud::with('usuario', 'programaDestino')->findOrFail($solicitudId);
           $usuario = $solicitud->usuario;

           // Prepara datos para las plantillas de correo
           $datos = [
               'primer_nombre' => $usuario->primer_nombre,
               'segundo_nombre' => $usuario->segundo_nombre,
               'primer_apellido' => $usuario->primer_apellido,
               'segundo_apellido' => $usuario->segundo_apellido,
               'email' => $usuario->email,
               'programa_destino' => $solicitud->programaDestino->nombre ?? 'No especificado',
               'finalizo_estudios' => $solicitud->finalizo_estudios ? 'Sí' : 'No',
               'fecha_solicitud' => $solicitud->fecha_solicitud,
               'estado' => $solicitud->estado,
               'numero_radicado' => $solicitud->numero_radicado
           ];

           // Envío de notificaciones a diferentes roles/departamentos
           Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new SecretariaMailable($datos));
           Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new CoordinacionMailable($datos));
           //Mail::to($usuario->email)->send(new AspiranteMailable($datos));

           //Aspirante prueba para ver si se envía el correo y si llega
           //Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new AspiranteMailable($datos));

           Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new ControlSeguimientoMailable($datos));
           Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new VicerrectoriaMailable($datos));

           // Registra éxito en el log
           Log::info('Correo enviado exitosamente a Secretaría', ['radicado' => $solicitud->numero_radicado]);

           return true;
       } catch (\Exception $e) {
           // Registra detalles del error en el log
           Log::error('Error al enviar correo a Secretaría', [
               'error email notificacion secretaria' => $e->getMessage(),
               'trace email notificacion secretaria' => $e->getTraceAsString()
           ]);

           return false;
       }
   }

   /**
    * Actualiza los datos de una solicitud existente
    *
    * @param \Illuminate\Http\Request $request Nuevos datos de la solicitud
    * @param int $id Identificador de la solicitud a actualizar
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function actualizarSolicitud(Request $request, $id)
   {
       try {
           // Busca la solicitud a actualizar
           $solicitud = Solicitud::find($id);

           // Verifica si existe
           if (!$solicitud) {
               return response()->json([
                   'mensaje' => 'Solicitud no encontrada'
               ], 404);
           }

           // Actualiza los campos con los nuevos valores
           $solicitud->usuario_id = $request->usuario_id;
           $solicitud->programa_destino_id = $request->programa_destino_id;
           $solicitud->finalizo_estudios = $request->finalizo_estudios;
           $solicitud->fecha_finalizacion_estudios = $request->fecha_finalizacion_estudios;
           $solicitud->fecha_ultimo_semestre_cursado = $request->fecha_ultimo_semestre_cursado;
           $solicitud->estado = $request->estado;
           $solicitud->ruta_pdf_resolucion = $request->ruta_pdf_resolucion;
           $solicitud->save();

           // Respuesta de éxito
           return response()->json([
               'mensaje' => 'Solicitud actualizada correctamente'
           ], 200);
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al actualizar la solicitud',
               'error' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Elimina una solicitud y sus registros relacionados
    *
    * @param int $id Identificador de la solicitud a eliminar
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function eliminarSolicitud($id)
   {
       try {
           // Inicia transacción para asegurar integridad en el borrado
           DB::beginTransaction();

           // Verifica existencia de la solicitud
           $solicitud = Solicitud::find($id);

           if (!$solicitud) {
               return response()->json([
                   'mensaje' => 'Solicitud no encontrada'
               ], 404);
           }

           // Elimina primero los registros dependientes en historial
           DB::table('historial_homologaciones')
               ->where('solicitud_id', $id)
               ->delete();

           // Elimina la solicitud principal
           $solicitud->delete();

           // Confirma transacción
           DB::commit();

           // Respuesta de éxito
           return response()->json([
               'mensaje' => 'Solicitud eliminada correctamente'
           ], 200);
       } catch (\Exception $e) {
           // Revierte cambios en caso de error
           DB::rollBack();

           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al eliminar la solicitud',
               'error' => $e->getMessage()
           ], 500);
       }
   }
}
