<?php

namespace App\Http\Controllers;

use App\Mail\VicerrectoriaMailable;
use App\Models\HomologacionAsignatura;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificacionVicerrectoriaController extends Controller
{
    /**
     * Envía un correo a Vicerrectoría con los detalles de una homologación
     * Este método solo debe ser llamado cuando el estado es "En revisión"
     *
     * @param int $homologacion_id Identificador de la homologación
     * @return bool Resultado de la operación
     */
    public function enviarCorreoHomologacion($homologacion_id)
    {
        try {
            // Busca la homologación con las relaciones necesarias
            $homologacion = HomologacionAsignatura::with([
                'solicitud.usuario',
                'solicitud.programaDestino',
                'asignaturaOrigen',
                'asignaturaDestino'
            ])->findOrFail($homologacion_id);

            // Se accede al usuario desde la solicitud relacionada
            $usuario = $homologacion->solicitud->usuario;
            $solicitud = $homologacion->solicitud;

            // Verificación adicional de que el estado sea "En revisión"
            if ($solicitud->estado !== 'En revisión') {
                Log::info('No se envió correo a Vicerrectoría porque el estado no es "En revisión"', [
                    'homologacion_id' => $homologacion_id,
                    'estado_actual' => $solicitud->estado
                ]);
                return false;
            }

            // Prepara los datos para enviar al mailable
            $datos = [
                'primer_nombre' => $usuario->primer_nombre,
                'segundo_nombre' => $usuario->segundo_nombre ?? '',
                'primer_apellido' => $usuario->primer_apellido,
                'segundo_apellido' => $usuario->segundo_apellido ?? '',
                'email' => $usuario->email,
                'solicitud_id' => $homologacion->solicitud_id,
                'asignatura_origen' => $homologacion->asignaturaOrigen->nombre ?? 'No especificado',
                'asignatura_destino' => $homologacion->asignaturaDestino->nombre ?? 'No especificado',
                'nota_destino' => $homologacion->nota_destino ?? 'No asignada',
                'fecha' => $homologacion->fecha ?? now()->format('Y-m-d'),
                'comentarios' => $homologacion->comentarios ?? 'Sin comentarios',
                'estado' => $solicitud->estado,
                'numero_radicado' => $solicitud->numero_radicado ?? 'No disponible',
                'programa_destino' => $solicitud->programaDestino->nombre ?? 'No especificado',
                'finalizo_estudios' => $solicitud->finalizo_estudios ? 'Sí' : 'No',
                'fecha_solicitud' => $solicitud->fecha_solicitud
            ];

            // Envío del correo a la dirección indicada
            Mail::to("brayner.trochez.o@uniautonoma.edu.co")->send(new VicerrectoriaMailable($datos));

            // Registro del éxito del envío en el log
            Log::info('Correo enviado exitosamente a Vicerrectoría', [
                'homologacion_id' => $homologacion_id,
                'estado' => $solicitud->estado
            ]);

            return true;
        } catch (\Exception $e) {
            // En caso de error, se registra el mensaje y el stack trace
            Log::error('Error al enviar correo a Vicerrectoría', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Cambia el estado de una homologación a "En revisión" y envía notificación a Vicerrectoría
     *
     * @param int $homologacion_id Identificador de la homologación
     * @return bool Resultado de la operación
     */
    public function cambiarEstadoEnRevision($homologacion_id)
    {
        try {
            // Busca la homologación
            $homologacion = HomologacionAsignatura::with([
                'solicitud.usuario',
                'solicitud.programaDestino',
                'asignaturaOrigen',
                'asignaturaDestino'
            ])->findOrFail($homologacion_id);

            // Recupera la solicitud relacionada
            $solicitud = $homologacion->solicitud;

            // Cambia el estado a "En revisión" si no lo está ya
            if ($solicitud->estado !== 'En revisión') {
                $solicitud->estado = 'En revisión';
                $solicitud->save();

                Log::info('Estado de solicitud cambiado a "En revisión"', [
                    'homologacion_id' => $homologacion_id,
                    'solicitud_id' => $solicitud->id_solicitud
                ]);
            }

            // Envía el correo de notificación a Vicerrectoría
            return $this->enviarCorreoHomologacion($homologacion_id);

        } catch (\Exception $e) {
            // En caso de error, se registra el mensaje y el stack trace
            Log::error('Error al cambiar estado a "En revisión" o enviar correo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Método alternativo que acepta solicitud_id en lugar de homologacion_id
     * Útil para enviar notificaciones desde el controlador de solicitudes
     *
     * @param int $solicitud_id Identificador de la solicitud
     * @return bool Resultado de la operación
     */
    public function notificarVicerrectoriaPorSolicitud($solicitud_id)
    {
        try {
            // Busca la solicitud con relaciones necesarias
            $solicitud = Solicitud::with('usuario', 'programaDestino')->findOrFail($solicitud_id);

            // Verifica que el estado sea "En revisión"
            if ($solicitud->estado !== 'En Revisión') {
                Log::info('No se envió correo a Vicerrectoría porque el estado no es "En revisión"', [
                    'solicitud_id' => $solicitud_id,
                    'estado_actual' => $solicitud->estado
                ]);
                return false;
            }

            $usuario = $solicitud->usuario;

            // Busca la primera homologación relacionada para obtener datos adicionales
            $homologacion = HomologacionAsignatura::where('solicitud_id', $solicitud_id)
                ->with(['asignaturaOrigen', 'asignaturaDestino'])
                ->first();

            // Prepara los datos básicos para el correo
            $datos = [
                'primer_nombre' => $usuario->primer_nombre,
                'segundo_nombre' => $usuario->segundo_nombre ?? '',
                'primer_apellido' => $usuario->primer_apellido,
                'segundo_apellido' => $usuario->segundo_apellido ?? '',
                'email' => $usuario->email,
                'solicitud_id' => $solicitud_id,
                'estado' => $solicitud->estado,
                'numero_radicado' => $solicitud->numero_radicado ?? 'No disponible',
                'programa_destino' => $solicitud->programaDestino->nombre ?? 'No especificado',
                'finalizo_estudios' => $solicitud->finalizo_estudios ? 'Sí' : 'No',
                'fecha_solicitud' => $solicitud->fecha_solicitud
            ];

            // Añade datos de homologación si están disponibles
            if ($homologacion) {
                $datos['asignatura_origen'] = $homologacion->asignaturaOrigen->nombre ?? 'No especificado';
                $datos['asignatura_destino'] = $homologacion->asignaturaDestino->nombre ?? 'No especificado';
                $datos['nota_destino'] = $homologacion->nota_destino ?? 'No asignada';
                $datos['fecha'] = $homologacion->fecha ?? now()->format('Y-m-d');
                $datos['comentarios'] = $homologacion->comentarios ?? 'Sin comentarios';
            }

            // Envío del correo a la dirección indicada - usar try/catch específico para capturar errores de correo
            try {
                Mail::to("brayner.trochez.o@uniautonoma.edu.co")->send(new VicerrectoriaMailable($datos));

                // Registro del éxito del envío en el log
                Log::info('Correo enviado exitosamente a Vicerrectoría desde solicitud', [
                    'solicitud_id' => $solicitud_id,
                    'estado' => $solicitud->estado
                ]);

                return true;
            } catch (\Exception $mailError) {
                Log::error('Error específico al enviar el correo a Vicerrectoría', [
                    'error' => $mailError->getMessage(),
                    'trace' => $mailError->getTraceAsString()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            // En caso de error general, se registra el mensaje y el stack trace
            Log::error('Error al preparar datos para notificar a Vicerrectoría desde solicitud', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }
}
