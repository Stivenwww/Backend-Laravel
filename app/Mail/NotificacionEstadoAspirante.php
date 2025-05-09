<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionEstadoAspirante extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $mensaje;

    /**
     * Create a new message instance.
     *
     * @param $usuario
     * @param $mensaje
     */
    public function __construct($usuario, $mensaje)
    {
        $this->usuario = $usuario;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notificacion_estado_aspirante')
                    ->with([
                        'usuario' => $this->usuario,
                        'mensaje' => $this->mensaje,
                    ]);
    }
}
