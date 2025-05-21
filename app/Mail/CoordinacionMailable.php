<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoordinacionMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;
    protected $asunto;

    /**
     * Create a new message instance.
     *
     * @param array $data Datos del usuario y la solicitud
     * @param string $asunto Asunto personalizable del correo
     */
    public function __construct($data, $asunto = null)
    {
        $this->datos = $data;
        $this->asunto = $asunto ?? 'Solicitud de Homologación - ' . $data['estado'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

          return $this->subject($this->asunto)
                ->view('emails.coordinacion');
}
}
