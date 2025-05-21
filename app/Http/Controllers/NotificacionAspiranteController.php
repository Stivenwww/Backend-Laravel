<?php

// Se define el espacio de nombres donde se encuentra este controlador
namespace App\Http\Controllers;

// Se importan las clases necesarias
use App\Mail\AspiranteMailable;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// Se declara el controlador NotificacionAspiranteController que extiende de Controller
class NotificacionAspiranteController extends Controller
{
    /**
     * Envía un correo de notificación al aspirante basado en el estado de su solicitud
     *
     * @param int $solicitud_id El ID de la solicitud
     * @return bool Resultado de la operación de envío
     */
    public function notificarAspirantePorSolicitud($solicitud_id)
    {
        try {
            // Busca la solicitud con sus relaciones
            $solicitud = Solicitud::with(['usuario', 'programaDestino'])->findOrFail($solicitud_id);

            // Obtiene el usuario relacionado con la solicitud
            $usuario = $solicitud->usuario;

            if (!$usuario) {
                Log::error('No se encontró usuario asociado a la solicitud', ['solicitud_id' => $solicitud_id]);
                return false;
            }

            // Prepara los datos que se enviarán al correo
            $datos = [
                'primer_nombre' => $usuario->primer_nombre,
                'segundo_nombre' => $usuario->segundo_nombre ?? '',
                'primer_apellido' => $usuario->primer_apellido,
                'segundo_apellido' => $usuario->segundo_apellido ?? '',
                'email' => $usuario->email,
                'programa_destino' => $solicitud->programaDestino->nombre ?? 'No especificado',
                'finalizo_estudios' => $solicitud->finalizo_estudios ? 'Sí' : 'No',
                'fecha_solicitud' => $solicitud->fecha_solicitud,
                'estado' => $solicitud->estado,
                'numero_radicado' => $solicitud->numero_radicado
            ];

            // Envía el correo electrónico al usuario
            //Mail::to($usuario->email)->send(new AspiranteMailable($datos));

            // Para propósitos de prueba, enviar también a una dirección conocida
            // Comentar o eliminar esta línea en producción si no es necesaria
            Mail::to('brayner.trochez.o@uniautonoma.edu.co')->send(new AspiranteMailable($datos));

            // Registra en el log un mensaje de éxito
            Log::info('Correo enviado exitosamente al aspirante', [
                'radicado' => $solicitud->numero_radicado,
                'estado' => $solicitud->estado
            ]);

            return true;

        } catch (\Exception $e) {
            // En caso de error, registra el mensaje y el trace de la excepción en el log
            Log::error('Error al enviar correo al aspirante', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'solicitud_id' => $solicitud_id
            ]);

            return false;
        }
    }
}
