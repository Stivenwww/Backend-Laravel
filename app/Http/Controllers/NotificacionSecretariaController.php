<?php

// Se define el espacio de nombres de este controlador
namespace App\Http\Controllers;

// Se importan las clases necesarias para el funcionamiento
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // Permite enviar correos electrónicos
use Illuminate\Support\Facades\Log;  // Permite registrar eventos o errores
use App\Models\Solicitud; // Modelo que representa la solicitud en la base de datos
use App\Mail\SecretariaMailable; // Clase mailable para la notificación a Secretaría

// Se declara el controlador NotificacionSecretariaController que extiende de Controller
class NotificacionSecretariaController extends Controller
{
    // Método que envía un correo basado en una solicitud específica
    public function enviarCorreoSolicitud($solicitud_id)
    {
        try {
            // Busca la solicitud junto con su relación 'usuario' usando el ID dado
            // Lanza excepción si no la encuentra
            $solicitud = Solicitud::with('usuario')->findOrFail($solicitud_id);

            // Obtiene el usuario relacionado con la solicitud
            $usuario = $solicitud->usuario;

            // Arreglo con los datos que serán enviados en el correo
            $datos = [
                'primer_nombre' => $usuario->primer_nombre,
                'segundo_nombre' => $usuario->segundo_nombre,
                'primer_apellido' => $usuario->primer_apellido,
                'segundo_apellido' => $usuario->segundo_apellido,
                'email' => $usuario->email,

                // Programa destino, con valor por defecto si no está definido
                'programa_destino' => $solicitud->programaDestino->nombre ?? 'No especificado',

                // Conversión de booleano a texto legible
                'finalizo_estudios' => $solicitud->finalizo_estudios ? 'Sí' : 'No',

                // Fecha de creación de la solicitud
                'fecha_solicitud' => $solicitud->fecha_solicitud,

                // Estado actual de la solicitud
                'estado' => $solicitud->estado,

                // Número de radicado asociado a la solicitud
                'numero_radicado' => $solicitud->numero_radicado
            ];

            // Envía el correo a la dirección de Secretaría utilizando el mailable personalizado
            Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new SecretariaMailable($datos));

            // Si se envía con éxito, se registra el evento en el log
            Log::info('Correo enviado exitosamente a Secretaría', ['radicado' => $solicitud->numero_radicado]);

        } catch (\Exception $e) {
            // Si ocurre algún error, se registra el mensaje y la traza completa en el log
            Log::error('Error al enviar correo a Secretaría', [
                'error email notificacion secretaria' => $e->getMessage(),
                'trace email notificacion secretaria' => $e->getTraceAsString()
            ]);
        }
    }
}
