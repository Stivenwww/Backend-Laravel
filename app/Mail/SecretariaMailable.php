<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecretariaMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Datos de la solicitud para usar en la vista
     *
     * @var array
     */
    public $datos;

    /**
     * Mensaje personalizado según el estado
     *
     * @var string
     */
    public $mensaje;

    /**
     * Create a new message instance.
     *
     * @param array $datos Datos de la solicitud
     * @param string|null $mensaje Mensaje personalizado (opcional)
     */
    public function __construct($datos, $mensaje = null)
    {
        $this->datos = $datos;

        // Si no se proporciona un mensaje, generarlo según el estado
        $this->mensaje = $mensaje ?? $this->obtenerMensajePorEstado($datos['estado']);

        // También asignamos las variables individuales para mantener compatibilidad
        foreach ($datos as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $asunto = $this->generarAsuntoPorEstado();

        return $this->subject($asunto)
                   ->view('emails.secretaria');
    }

    /**
     * Genera un asunto apropiado basado en el estado de la solicitud
     *
     * @return string
     */
    private function generarAsuntoPorEstado()
    {
        $prefijo = "Solicitud de Homologación #{$this->datos['numero_radicado']}";

        switch ($this->datos['estado']) {
            case 'Radicado':
                return "NUEVA {$prefijo} - Revisión Inicial";
            case 'En revisión':
                return "{$prefijo} - En Proceso de Revisión";
            case 'Aprobado':
                return "{$prefijo} - APROBADA";
            case 'Rechazado':
                return "{$prefijo} - RECHAZADA";
            case 'Cerrado':
                return "{$prefijo} - Proceso Finalizado";
            default:
                return "{$prefijo} - Actualización de Estado";
        }
    }

    /**
     * Determina el mensaje apropiado según el estado de la solicitud
     *
     * @param string $estado Estado actual de la solicitud
     * @return string Mensaje personalizado
     */
    private function obtenerMensajePorEstado($estado)
    {

        switch ($estado) {
            case 'Radicado':
                return 'Se ha recibido una nueva solicitud de homologación que requiere su atención. ' .
                       'Por favor, proceda con la revisión inicial de documentos y requisitos.';

            case 'En revisión':
                return 'La solicitud ha pasado a estado de revisión. ' .
                       'Se requiere su seguimiento para coordinar el análisis académico correspondiente.';

            case 'Aprobado':
                return 'La solicitud de homologación ha sido APROBADA. ' .
                       'Se requiere su gestión para la emisión de resolución y notificación oficial.';

            case 'Rechazado':
                return 'La solicitud de homologación ha sido RECHAZADA. ' .
                       'Favor proceder con los procedimientos administrativos para cierre del expediente.';

            case 'Cerrado':
                return 'El proceso de homologación ha sido cerrado. ' .
                       'Favor archivar el expediente en el repositorio digital correspondiente.';

            default:
                return 'La solicitud de homologación ha cambiado de estado. ' .
                       'Se requiere su intervención para continuar con el proceso administrativo.';
        }
    }
}
