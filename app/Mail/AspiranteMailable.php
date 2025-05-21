<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AspiranteMailable extends Mailable
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
    public $titulo_correo;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->primer_nombre = $data['primer_nombre'];
        $this->segundo_nombre = $data['segundo_nombre'] ?? '';
        $this->primer_apellido = $data['primer_apellido'];
        $this->segundo_apellido = $data['segundo_apellido'] ?? '';
        $this->email = $data['email'];
        $this->programa_destino = $data['programa_destino'] ?? 'No especificado';
        $this->finalizo_estudios = $data['finalizo_estudios'] ?? 'No especificado';
        $this->fecha_solicitud = $data['fecha_solicitud'] ?? date('Y-m-d');
        $this->estado = $data['estado'] ?? 'Radicado';
        $this->numero_radicado = $data['numero_radicado'] ?? 'No disponible';

        // Configurar título y mensaje según el estado
        $this->configurarMensajes();
    }

    /**
     * Configura los mensajes personalizados según el estado de la solicitud
     */
    private function configurarMensajes()
    {
        switch ($this->estado) {
            case 'Radicado':
                $this->titulo_correo = 'Confirmación de Solicitud de Homologación';
                $this->mensaje_personalizado = 'Su solicitud de homologación ha sido registrada exitosamente con el número de radicado '.$this->numero_radicado.'. Le recomendamos estar atento/a a la plataforma, donde la Corporación Universitaria Autónoma del Cauca le notificará los pasos a seguir.';
                break;

            case 'En revisión':
                $this->titulo_correo = 'Actualización de Estado - Solicitud en Revisión';
                $this->mensaje_personalizado = 'Su solicitud de homologación con número de radicado '.$this->numero_radicado.' ha pasado al estado "En revisión". El comité académico está evaluando su caso y pronto recibirá una respuesta.';
                break;

            case 'Aprobado':
                $this->titulo_correo = '¡Felicidades! Solicitud de Homologación Aprobada';
                $this->mensaje_personalizado = '¡Felicidades! Su solicitud de homologación con número de radicado '.$this->numero_radicado.' ha sido APROBADA. Por favor ingrese al sistema para conocer los detalles y los pasos a seguir para formalizar el proceso.';
                break;

            case 'Rechazado':
                $this->titulo_correo = 'Respuesta a Solicitud de Homologación';
                $this->mensaje_personalizado = 'Lamentamos informarle que su solicitud de homologación con número de radicado '.$this->numero_radicado.' ha sido RECHAZADA. Para conocer los motivos detallados, por favor ingrese al sistema o comuníquese con la oficina de admisiones.';
                break;

            case 'Cerrado':
                $this->titulo_correo = 'Cierre de Proceso de Homologación';
                $this->mensaje_personalizado = 'El proceso de homologación con número de radicado '.$this->numero_radicado.' ha sido CERRADO. Para más información sobre su caso y los pasos a seguir, por favor ingrese al sistema.';
                break;

            default:
                $this->titulo_correo = 'Actualización de Solicitud de Homologación';
                $this->mensaje_personalizado = 'Su solicitud de homologación con número de radicado '.$this->numero_radicado.' ha sido actualizada. Por favor ingrese al sistema para conocer el estado actual.';
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->titulo_correo)
                    ->view('emails.aspirante');
    }
}
