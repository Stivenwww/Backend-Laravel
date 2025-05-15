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
    public $programa_destino;
    public $finalizo_estudios;
    public $fecha_solicitud;
    public $estado;
    public $numero_radicado;
    public $asignatura_origen;
    public $asignatura_destino;
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
        $this->primer_nombre = $data['primer_nombre'] ?? '';
        $this->segundo_nombre = $data['segundo_nombre'] ?? '';


        $this->primer_apellido = $data['primer_apellido'] ?? '';
        $this->segundo_apellido = $data['segundo_apellido'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->solicitud_id = $data['solicitud_id'] ?? null;
        $this->programa_destino = $data['programa_destino'] ?? 'No especificado';
        $this->finalizo_estudios = $data['finalizo_estudios'] ?? 'No';
        $this->fecha_solicitud = $data['fecha_solicitud'] ?? now()->format('Y-m-d');
        $this->estado = $data['estado'] ?? 'En revisión';
        $this->numero_radicado = $data['numero_radicado'] ?? 'No disponible';
        $this->asignatura_origen = $data['asignatura_origen'] ?? null;
        $this->asignatura_destino = $data['asignatura_destino'] ?? null;
        $this->nota_destino = $data['nota_destino'] ?? null;
        $this->fecha = $data['fecha'] ?? null;
        $this->comentarios = $data['comentarios'] ?? null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Solicitud de Homologación En Revisión')
                    ->view('emails.vicerrectoria');
    }
}
