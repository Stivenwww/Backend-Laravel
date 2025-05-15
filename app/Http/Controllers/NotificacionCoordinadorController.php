<?php

namespace App\Http\Controllers;

use App\Mail\CoordinacionMailable;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificacionCoordinadorController extends Controller
{
    /**
     * Envía una notificación por correo al coordinador según el estado de la solicitud
     *
     * @param int $solicitud_id ID de la solicitud
     * @return bool Resultado de la operación de envío
     */
    public function notificarCoordinadorPorSolicitud($solicitud_id)
    {
        try {
            // Buscar la solicitud con sus relaciones
            $solicitud = Solicitud::with(['usuario', 'programaDestino'])->findOrFail($solicitud_id);
            $usuario = $solicitud->usuario;

            // Preparar datos para la plantilla
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

            // Personalizar el asunto según el estado
            $asunto = $this->obtenerAsuntoSegunEstado($solicitud->estado, $solicitud->numero_radicado);

            // Obtener correo de coordinador - por ahora usamos un correo de prueba
            $correoCoordinador = 'brayner.trochez.o@uniautonoma.edu.co';

            // Enviar correo
            Mail::to($correoCoordinador)->send(new CoordinacionMailable($datos, $asunto));

            // Registrar éxito en log
            Log::info('Correo enviado exitosamente al Coordinador', [
                'radicado' => $solicitud->numero_radicado,
                'estado' => $solicitud->estado
            ]);

            return true;
        } catch (\Exception $e) {
            // Registrar error en log
            Log::error('Error al enviar correo al Coordinador', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'solicitud_id' => $solicitud_id
            ]);

            return false;
        }
    }

}
