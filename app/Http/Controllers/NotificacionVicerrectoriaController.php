<?php

// Define el espacio de nombres del controlador
namespace App\Http\Controllers;

// Importación de clases necesarias
use App\Mail\VicerrectoriaMailable; // Clase mailable que estructura el correo para Vicerrectoría
use App\Models\HomologacionAsignatura; // Modelo que representa una homologación de asignatura
use Illuminate\Support\Facades\Log; // Permite registrar eventos y errores en los logs
use Illuminate\Support\Facades\Mail; // Facade para el envío de correos

// Definición de la clase del controlador
class NotificacionVicerrectoriaController extends Controller
{
    // Método para enviar un correo sobre una homologación específica
    public function enviarCorreoHomologacion($homologacion_id)
    {
        try {
            // Se busca la homologación con las relaciones necesarias: solicitud, usuario, asignaturas
            $homologacion = HomologacionAsignatura::with([
                'solicitud.usuario',      // Relación con el usuario que hizo la solicitud
                'asignaturaOrigen',       // Asignatura desde la cual se homologa
                'asignaturaDestino'       // Asignatura a la que se homologa
            ])->findOrFail($homologacion_id); // Si no se encuentra, lanza una excepción

            // Se accede al usuario desde la solicitud relacionada
            $usuario = $homologacion->solicitud->usuario;

            // Se preparan los datos para enviar al mailable
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

            // Envío del correo a la dirección indicada usando la clase mailable correspondiente
            Mail::to("brayner.trochez.o@uniautonoma.edu.co")->send(new VicerrectoriaMailable($datos));

            // Registro del éxito del envío en el log
            Log::info('Correo enviado exitosamente sobre homologación', ['homologacion_id' => $homologacion_id]);

        } catch (\Exception $e) {
            // En caso de error, se registra el mensaje y el stack trace
            Log::error('Error al enviar correo sobre homologación', [
                'error email notificacion homologacion' => $e->getMessage(),
                'trace email notificacion homologacion' => $e->getTraceAsString()
            ]);
        }
    }
}
