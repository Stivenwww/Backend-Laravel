<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Solicitud;

class SolicitudControllerApi extends Controller
{
    // Método para obtener todas las solicitudes
    public function traerSolicitudes()
    {
        try {
            // Llamada al procedimiento almacenado para obtener todas las solicitudes
            $solicitudes = DB::select('CALL ObtenerSolicitudes()');
            return response()->json($solicitudes);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener las solicitudes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para obtener una solicitud por ID
    public function llevarSolicitud($id)
    {
        try {
            // Llamada al procedimiento almacenado para obtener una solicitud por ID
            $solicitud = DB::select('CALL ObtenerSolicitudPorId(?)', [$id]);

            if (!empty($solicitud)) {
                return response()->json([
                    'mensaje' => 'Solicitud encontrada',
                    'datos' => $solicitud[0] // Accedemos al primer resultado
                ], 200);
            } else {
                return response()->json([
                    'mensaje' => 'Solicitud no encontrada',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al obtener la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para insertar una nueva solicitud
    public function insertarSolicitud(Request $request)
    {
        try {
            // Utilizamos el modelo para aprovechar la generación automática del número de radicado
            $solicitud = new Solicitud();
            $solicitud->usuario_id = $request->usuario_id;
            $solicitud->programa_destino_id = $request->programa_destino_id;
            $solicitud->finalizo_estudios = $request->finalizo_estudios;
            $solicitud->fecha_finalizacion_estudios = $request->fecha_finalizacion_estudios;
            $solicitud->fecha_ultimo_semestre_cursado = $request->fecha_ultimo_semestre_cursado;
            $solicitud->estado = $request->estado ?? 'Radicado'; // Valor predeterminado si no se proporciona
            $solicitud->ruta_pdf_resolucion = $request->ruta_pdf_resolucion;
            $solicitud->save();

            return response()->json([
                'mensaje' => 'Solicitud insertada correctamente',
                'numero_radicado' => $solicitud->numero_radicado
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al insertar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar una solicitud
    public function actualizarSolicitud(Request $request, $id)
    {
        try {
            // Primero obtenemos la solicitud existente
            $solicitud = Solicitud::find($id);

            if (!$solicitud) {
                return response()->json([
                    'mensaje' => 'Solicitud no encontrada'
                ], 404);
            }

            // Actualizamos los campos
            $solicitud->usuario_id = $request->usuario_id;
            $solicitud->programa_destino_id = $request->programa_destino_id;
            $solicitud->finalizo_estudios = $request->finalizo_estudios;
            $solicitud->fecha_finalizacion_estudios = $request->fecha_finalizacion_estudios;
            $solicitud->fecha_ultimo_semestre_cursado = $request->fecha_ultimo_semestre_cursado;
            $solicitud->estado = $request->estado;
            $solicitud->ruta_pdf_resolucion = $request->ruta_pdf_resolucion;
            $solicitud->save();

            return response()->json([
                'mensaje' => 'Solicitud actualizada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => 'Error al actualizar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método para eliminar una solicitud
    public function eliminarSolicitud($id)
    {
        try {
            // Usamos una transacción para asegurar que todo se ejecute o nada
            DB::beginTransaction();

            // Primero verificamos si la solicitud existe
            $solicitud = Solicitud::find($id);

            if (!$solicitud) {
                return response()->json([
                    'mensaje' => 'Solicitud no encontrada'
                ], 404);
            }

            // Eliminamos primero los registros del historial
            DB::table('historial_homologaciones')
                ->where('solicitud_id', $id)
                ->delete();

            // Ahora eliminamos la solicitud
            $solicitud->delete();

            DB::commit();

            return response()->json([
                'mensaje' => 'Solicitud eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'mensaje' => 'Error al eliminar la solicitud',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
