<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ControlSeguimientoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $primer_nombre;
    public $segundo_nombre;
    public $primer_apellido;
    public $segundo_apellido;
    public $email;
    public $programa_destino;
    public $finalizo_estudios;
    public $fecha_solicitud;
    public $estado;
    public $numero_radicado;
    public $mensaje_personalizado;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->primer_nombre = $data['primer_nombre'];
        $this->segundo_nombre = $data['segundo_nombre'];
        $this->primer_apellido = $data['primer_apellido'];
        $this->segundo_apellido = $data['segundo_apellido'];
        $this->email = $data['email'];
        $this->programa_destino = $data['programa_destino'];
        $this->finalizo_estudios = $data['finalizo_estudios'];
        $this->fecha_solicitud = $data['fecha_solicitud'];
        $this->estado = $data['estado'];
        $this->numero_radicado = $data['numero_radicado'];
        $this->mensaje_personalizado = $this->getMensajePersonalizado($data['estado']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $asunto = $this->getAsuntoSegunEstado($this->estado);

        return $this->subject($asunto)
                    ->view('emails.control');
    }

    /**
     * Obtiene el asunto del correo según el estado de la solicitud
     *
     * @param string $estado
     * @return string
     */
    private function getAsuntoSegunEstado($estado)
    {
        switch ($estado) {
            case 'Radicado':
                return 'Nueva Solicitud de Homologación Radicada';
            case 'En revisión':
                return 'Solicitud de Homologación en Revisión';
            case 'Aprobado':
                return 'Solicitud de Homologación Aprobada';
            case 'Rechazado':
                return 'Solicitud de Homologación Rechazada';
            case 'Cerrado':
                return 'Solicitud de Homologación Cerrada';
            default:
                return 'Actualización de Solicitud de Homologación';
        }
    }

    /**
     * Obtiene el mensaje personalizado según el estado de la solicitud
     *
     * @param string $estado
     * @return string
     */
    private function getMensajePersonalizado($estado)
    {
        switch ($estado) {
            case 'Radicado':
                return 'Se ha radicado una nueva solicitud de homologación en el sistema. Por favor, proceda a revisar la documentación adjunta y asigne un evaluador para continuar con el proceso.';
            case 'En revisión':
                return 'La solicitud de homologación ha pasado a estado de revisión. El comité evaluador está analizando la documentación presentada para determinar la viabilidad de la homologación.';
            case 'Aprobado':
                return 'La solicitud de homologación ha sido APROBADA por el comité evaluador. Por favor, continúe con los procesos administrativos correspondientes para registrar las asignaturas homologadas en el sistema académico.';
            case 'Rechazado':
                return 'La solicitud de homologación ha sido RECHAZADA por el comité evaluador. Verifique las observaciones registradas para conocer los motivos del rechazo.';
            case 'Cerrado':
                return 'El proceso de homologación ha sido completado y cerrado. Todos los registros han sido actualizados en el sistema académico.';
            default:
                return 'La solicitud de homologación ha sido actualizada. Por favor, verifique el estado actual en el sistema.';
        }
    }
}
