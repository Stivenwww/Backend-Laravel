<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VicerrectoriaMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $primer_nombre;
    public $segundo_nombre;
    public $primer_apellido;
    public $segundo_apellido;
    public $email;

    public $solicitud_id;
    public $asignatura_origen_id;
    public $asignatura_destino_id;
    public $nota_destino;
    public $fecha;
    public $comentarios;

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
        $this->solicitud_id = $data['solicitud_id'];
        $this->asignatura_origen_id = $data['asignatura_origen_id'];
        $this->asignatura_destino_id = $data['asignatura_destino_id'];
        $this->nota_destino = $data['nota_destino'];
        $this->fecha = $data['fecha'];
        $this->comentarios = $data['comentarios'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nueva Homologación de Asignatura')
                    ->view('emails.Viserrectoria');
    }
}
