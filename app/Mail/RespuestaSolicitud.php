<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RespuestaSolicitud extends Mailable
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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nueva Solicitud de Homologación')
                    ->view('emails.respuestaSolicitud');
    }

}
