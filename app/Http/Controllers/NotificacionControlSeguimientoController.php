<?php

// Se define el espacio de nombres de este controlador
namespace App\Http\Controllers;

// Se importan las clases necesarias
use App\Mail\AspiranteMailable; // Aunque no se usa en este archivo, está importada
use App\Mail\ControlSeguimientoMailable; // Clase mailable que se enviará
use App\Models\Solicitud; // Modelo de la solicitud
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Para registrar logs
use Illuminate\Support\Facades\Mail; // Para el envío de correos

// Se declara el controlador NotificacionControlSeguimientoController que hereda de Controller
class NotificacionControlSeguimientoController extends Controller
{
    // Método público para enviar un correo sobre una solicitud específica
    public function enviarCorreoSolicitud($solicitud_id)
    {
        try {
            // Busca la solicitud con su relación 'usuario' por el ID proporcionado
            // Lanza excepción si no se encuentra
            $solicitud = Solicitud::with('usuario')->findOrFail($solicitud_id);

            // Obtiene el usuario relacionado con la solicitud
            $usuario = $solicitud->usuario;

            // Crea un arreglo con los datos que se enviarán en el correo
            $datos = [
                'primer_nombre' => $usuario->primer_nombre,
                'segundo_nombre' => $usuario->segundo_nombre,
                'primer_apellido' => $usuario->primer_apellido,
                'segundo_apellido' => $usuario->segundo_apellido,
                'email' => $usuario->email,
                'estado' => $solicitud->estado,
                'numero_radicado' => $solicitud->numero_radicado
            ];

            // Envía el correo electrónico a una dirección fija (correo institucional)
            Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new ControlSeguimientoMailable($datos));

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
