<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Solicitud;
use App\Mail\SecretariaMailable;

class NotificacionSecretariaController extends Controller
{
    /**
     * Dirección de correo de Secretaría (podría establecerse en config/mail.php)
     */
    protected $correoSecretaria = 'brayner.trochez.o@uniautonoma.edu.co';

    /**
     * Envía notificación por correo a Secretaría basada en una solicitud específica
     *
     * @param int $solicitud_id ID de la solicitud
     * @return bool Resultado de la operación
     */
    public function notificarSecretariaPorSolicitud($solicitud_id)
    {
        try {
            // Busca la solicitud con relaciones necesarias
            $solicitud = Solicitud::with(['usuario', 'programaDestino'])->findOrFail($solicitud_id);

            // Obtiene el usuario relacionado con la solicitud
            $usuario = $solicitud->usuario;

            // Preparación de datos para el correo
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
                'numero_radicado' => $solicitud->numero_radicado,
                'solicitud_id' => $solicitud->id_solicitud
            ];

            // Obtiene mensaje personalizado según el estado
            $mensaje = $this->obtenerMensajePorEstado($solicitud->estado);

            // Envía el correo a la dirección de Secretaría
            Mail::to($this->correoSecretaria)
                ->send(new SecretariaMailable($datos, $mensaje));

            // Registra el envío exitoso
            Log::info('Correo enviado exitosamente a Secretaría', [
                'radicado' => $solicitud->numero_radicado,
                'estado' => $solicitud->estado,
                'mensaje' => substr($mensaje, 0, 50) . '...'
            ]);

            return true;
        } catch (\Exception $e) {
            // Registra detalles del error
            Log::error('Error al enviar correo a Secretaría', [
                'solicitud_id' => $solicitud_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

}
