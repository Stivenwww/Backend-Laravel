<?php

// Se define el espacio de nombres para este controlador
namespace App\Http\Controllers;

// Se importan las clases necesarias
use App\Mail\CoordinacionMailable; // Mailable personalizado para el coordinador
use App\Models\Solicitud; // Modelo de la solicitud
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Para registrar información o errores
use Illuminate\Support\Facades\Mail; // Para enviar correos

// Se define el controlador NotificacionCoordinadorController, que extiende de Controller
class NotificacionCoordinadorController extends Controller
{
    // Método público para enviar un correo con los datos de la solicitud al coordinador
    public function enviarCorreoSolicitud($solicitud_id)
    {
        try {
            // Se busca la solicitud con su relación 'usuario' por el ID dado
            // Si no se encuentra, lanza una excepción
            $solicitud = Solicitud::with('usuario')->findOrFail($solicitud_id);

            // Se obtiene el usuario relacionado con la solicitud
            $usuario = $solicitud->usuario;

            // Se preparan los datos que se enviarán en el correo
            $datos = [
                'primer_nombre' => $usuario->primer_nombre,
                'segundo_nombre' => $usuario->segundo_nombre,
                'primer_apellido' => $usuario->primer_apellido,
                'segundo_apellido' => $usuario->segundo_apellido,
                'email' => $usuario->email,

                // Se obtiene el nombre del programa destino; si no existe, se asigna "No especificado"
                'programa_destino' => $solicitud->programaDestino->nombre ?? 'No especificado',

                // Se convierte el valor booleano a texto legible
                'finalizo_estudios' => $solicitud->finalizo_estudios ? 'Sí' : 'No',

                // Fecha en la que se realizó la solicitud
                'fecha_solicitud' => $solicitud->fecha_solicitud,

                // Estado actual de la solicitud
                'estado' => $solicitud->estado,

                // Número de radicado único
                'numero_radicado' => $solicitud->numero_radicado
            ];

            // Enviar el correo electrónico al coordinador
            Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new CoordinacionMailable($datos));

            // Registrar en el log que el correo fue enviado exitosamente
            Log::info('Correo enviado exitosamente a Secretaría', ['radicado' => $solicitud->numero_radicado]);

        } catch (\Exception $e) {
            // En caso de error, registrar el mensaje y el trace de la excepción en el log
            Log::error('Error al enviar correo a Secretaría', [
                'error email notificacion secretaria' => $e->getMessage(),
                'trace email notificacion secretaria' => $e->getTraceAsString()
            ]);
        }
    }
}
