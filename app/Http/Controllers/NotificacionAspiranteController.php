<?php

// Se define el espacio de nombres donde se encuentra este controlador
namespace App\Http\Controllers;

// Se importan las clases necesarias

use App\Mail\AspiranteMailable;
use App\Mail\NotificacionEstadoAspirante;
use App\Mail\NotificacionEstadoAspiranteMailable;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// Se declara el controlador NotificacionAspiranteController que extiende de Controller
class NotificacionAspiranteController extends Controller
{
    // Método público para enviar un correo de notificación relacionado con una solicitud específica
    public function enviarCorreoSolicitud($solicitud_id)
    {
        try {
            // Busca la solicitud con su relación 'usuario' por el ID proporcionado
            // Si no se encuentra, lanza una excepción
            $solicitud = Solicitud::with('usuario')->findOrFail($solicitud_id);

            // Obtiene el usuario relacionado con la solicitud
            $usuario = $solicitud->usuario;

            // Prepara los datos que se enviarán al correo
            $datos = [
                'primer_nombre' => $usuario->primer_nombre,
                'segundo_nombre' => $usuario->segundo_nombre,
                'primer_apellido' => $usuario->primer_apellido,
                'segundo_apellido' => $usuario->segundo_apellido,
                'email' => $usuario->email,
                'estado' => $solicitud->estado,
                'numero_radicado' => $solicitud->numero_radicado
            ];

            // Determina el mensaje basado en el estado del usuario
            switch ($usuario->estado) {
                case 'pendiente':
                    $mensaje = 'Tu solicitud está siendo revisada.';
                    break;
                case 'aprobado':
                    $mensaje = '¡Felicidades! Has sido aprobado.';
                    break;
                case 'rechazado':
                    $mensaje = 'Tu solicitud fue rechazada.';
                    break;
                default:
                    $mensaje = 'El estado ha cambiado.';
            }

            // Envía el correo electrónico al usuario utilizando el nuevo Mailable
            //Mail::to($usuario->email)->send(new NotificacionEstadoAspiranteMailable($usuario, $mensaje));

            // Línea comentada: también se podría enviar el correo a una dirección fija (ejemplo institucional)
             Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new AspiranteMailable($datos));

            // Registra en el log un mensaje de éxito junto con el número de radicado
            Log::info('Correo enviado exitosamente a Secretaría', ['radicado' => $solicitud->numero_radicado]);

        } catch (\Exception $e) {
            // En caso de error, registra el mensaje y el trace de la excepción en el log
            Log::error('Error al enviar correo a Secretaría', [
                'error email notificacion secretaria' => $e->getMessage(),
                'trace email notificacion secretaria' => $e->getTraceAsString()
            ]);
        }
    }
}
