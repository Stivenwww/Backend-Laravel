<?php

namespace App\Http\Controllers;

use App\Mail\VicerrectoriaMailable;
use App\Mail\ViserrectoriaMailable;
use App\Models\HomologacionAsignatura;
use Illuminate\Support\Facades\Log;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Mail;

class NotificacionVicerrectoriaController extends Controller
{
    public function enviarCorreoHomologacion($homologacion_id)
        {
            try {
                $homologacion = HomologacionAsignatura::with(['solicitud.usuario', 'asignaturaOrigen', 'asignaturaDestino'])->findOrFail($homologacion_id);
                $usuario = $homologacion->solicitud->usuario;

                $datos = [
                    'primer_nombre' => $usuario->primer_nombre,
                    'segundo_nombre' => $usuario->segundo_nombre,
                    'primer_apellido' => $usuario->primer_apellido,
                    'segundo_apellido' => $usuario->segundo_apellido,
                    'email' => $usuario->email,
                    'solicitud_id' => $homologacion->solicitud_id,
                    'asignatura_origen' => $homologacion->asignaturaOrigen->nombre ?? 'No especificado',
                    'asignatura_destino' => $homologacion->asignaturaDestino->nombre ?? 'No especificado',
                    'nota_destino' => $homologacion->nota_destino ?? 'No asignada',
                    'fecha' => $homologacion->fecha,
                    'comentarios' => $homologacion->comentarios ?? 'Sin comentarios'
                ];

                Mail::to("brayner.trochez.o@uniautonoma.edu.co")->send(new VicerrectoriaMailable($datos));

                // Registrar éxito en el log
                Log::info('Correo enviado exitosamente sobre homologación', ['homologacion_id' => $homologacion_id]);

            } catch (\Exception $e) {
                // Registrar error en el log
                Log::error('Error al enviar correo sobre homologación', [
                    'error email notificacion homologacion' => $e->getMessage(),
                    'trace email notificacion homologacion' => $e->getTraceAsString()
                ]);
            }
        }
    }
