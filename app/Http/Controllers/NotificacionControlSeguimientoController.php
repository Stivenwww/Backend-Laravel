<?php

namespace App\Http\Controllers;

use App\Mail\ControlSeguimientoMailable;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificacionControlSeguimientoController extends Controller
{
    /**
     * Lista de correos del equipo de Control y Seguimiento
     */
    protected $correos_control_seguimiento = [
        "control.seguimiento@uniautonoma.edu.co"
    ];

    /**
     * Notifica al equipo de Control y Seguimiento sobre una solicitud
     *
     * @param int $solicitud_id
     * @return bool
     */
    public function notificarControlSeguimientoPorSolicitud($solicitud_id)
    {
        try {
            $solicitud = Solicitud::with('usuario', 'programaDestino')->find($solicitud_id);

            if (!$solicitud) {
                Log::error("Solicitud no encontrada para ID: {$solicitud_id}");
                return false;
            }

            $usuario = $solicitud->usuario;
            $programaDestino = $solicitud->programaDestino;

            $datos = [
                'primer_nombre' => $usuario->primer_nombre,
                'segundo_nombre' => $usuario->segundo_nombre ?? '',
                'primer_apellido' => $usuario->primer_apellido,
                'segundo_apellido' => $usuario->segundo_apellido ?? '',
                'email' => $usuario->email,
                'solicitud_id' => $solicitud->id_solicitud,
                'estado' => $solicitud->estado,
                'numero_radicado' => $solicitud->numero_radicado ?? 'No disponible',
                'programa_destino' => $programaDestino->nombre ?? 'No especificado',
                'finalizo_estudios' => $solicitud->finalizo_estudios ? 'Sí' : 'No',
                'fecha_solicitud' => $solicitud->fecha_solicitud
            ];

            // En ambiente de desarrollo, usar un correo de prueba
            if (app()->environment('local', 'development')) {
                $this->correos_control_seguimiento = ["correo.prueba@uniautonoma.edu.co"];
            }

            // Enviar correo a todos los destinatarios de Control y Seguimiento
            foreach ($this->correos_control_seguimiento as $correo) {
                Mail::to($correo)->send(new ControlSeguimientoMailable($datos));
            }

            Log::info("Notificación enviada a Control y Seguimiento para solicitud ID: {$solicitud_id}, Estado: {$solicitud->estado}");
            return true;
        } catch (\Exception $e) {
            Log::error("Error al enviar notificación a Control y Seguimiento", [
                'solicitud_id' => $solicitud_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Configura los destinatarios para el envío de correos según el estado
     *
     * @param string $estado
     * @return array
     */
    public function getDestinatariosPorEstado($estado)
    {
        // Puedes personalizar esta lista según los diferentes roles y estados
        $destinatarios = [
            'Radicado' => [
                "brayner.trochez.o@uniautonoma.edu.co"
            ],
            'En revisión' => [
                "brayner.trochez.o@uniautonoma.edu.co"
            ],
            'Aprobado' => [
                "registro.academico@uniautonoma.edu.co",
                "brayner.trochez.o@uniautonoma.edu.co"
            ],
            'Rechazado' => [
                "brayner.trochez.o@uniautonoma.edu.co"
            ],
            'Cerrado' => [
                "brayner.trochez.o@uniautonoma.edu.co"
            ]
        ];

        // Si estamos en ambiente de desarrollo, reemplazar por correo de prueba
        if (app()->environment('local', 'development')) {
            return ["correo.prueba@uniautonoma.edu.co"];
        }

        return $destinatarios[$estado] ?? ["control.seguimiento@uniautonoma.edu.co"];
    }
}
