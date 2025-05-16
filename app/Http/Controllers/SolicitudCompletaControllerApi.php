<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Solicitud;
use App\Models\SolicitudAsignatura;
use App\Models\Documento;
use Illuminate\Support\Facades\Storage;

class SolicitudCompletaControllerApi extends Controller
{
    public function update(Request $request)
{
    // Log request for debugging
    Log::info('Request data:', $request->all());

    // Validación básica
    $validator = Validator::make($request->all(), [
        'tipo_identificacion' => 'required|string|max:30',
        'numero_identificacion' => 'required|string|max:20',
        'primer_nombre' => 'required|string|max:50',
        'primer_apellido' => 'required|string|max:50',
        'email' => 'required|email|max:100',
        'telefono' => 'required|string|max:20',
        'direccion' => 'required|string',
        'pais' => 'present',
        'departamento' => 'present',
        'municipio' => 'present',
        'programa_origen' => 'present',
        'programa_destino' => 'required',
        'finalizo_estudios' => 'required|string',
        'numero_rad' => 'required|string',
        'password' => 'required|string|min:6',
        'materias' => 'required'
    ]);

    if ($validator->fails()) {
        Log::warning('Validation errors:', $validator->errors()->toArray());
        return response()->json([
            'error' => 'Error de validación',
            'message' => $validator->errors()->first(),
            'all_errors' => $validator->errors()->toArray()
        ], 422);
    }

    DB::beginTransaction();

    try {
        // Procesar JSON de materias
        $materiasJson = $request->input('materias');
        $materias = json_decode($materiasJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Error al decodificar JSON de materias: ' . json_last_error_msg());
        }

        if (!is_array($materias) || empty($materias)) {
            throw new \Exception('No se proporcionaron materias para homologar');
        }

        // 1. Buscar o crear usuario
        $usuario = User::where('email', $request->input('email'))->first();

        if ($usuario) {
            // Actualizar usuario existente
            $usuario->update([
                'tipo_identificacion' => $request->input('tipo_identificacion'),
                'numero_identificacion' => $request->input('numero_identificacion'),
                'primer_nombre' => $request->input('primer_nombre'),
                'segundo_nombre' => $request->input('segundo_nombre') ?: null,
                'primer_apellido' => $request->input('primer_apellido'),
                'segundo_apellido' => $request->input('segundo_apellido') ?: null,
                'telefono' => $request->input('telefono'),
                'direccion' => $request->input('direccion'),
                'pais' => $request->input('pais'),
                'departamento_id' => $request->input('departamento'),
                'municipio_id' => $request->input('municipio'),
                'institucion_origen' => $request->input('institucion_origen'),
                'facultad' => $request->input('tipo_formacion'),
            ]);
        } else {
            // Crear nuevo usuario
            $usuario = User::create([
                'tipo_identificacion' => $request->input('tipo_identificacion'),
                'numero_identificacion' => $request->input('numero_identificacion'),
                'primer_nombre' => $request->input('primer_nombre'),
                'segundo_nombre' => $request->input('segundo_nombre') ?: null,
                'primer_apellido' => $request->input('primer_apellido'),
                'segundo_apellido' => $request->input('segundo_apellido') ?: null,
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'telefono' => $request->input('telefono'),
                'direccion' => $request->input('direccion'),
                'pais' => $request->input('pais'),
                'departamento_id' => $request->input('departamento'),
                'municipio_id' => $request->input('municipio'),
                'institucion_origen' => $request->input('institucion_origen'),
                'facultad' => $request->input('tipo_formacion'),
            ]);
        }

        // 2. Crear solicitud con programa de destino correcto
        $solicitud = Solicitud::create([
            'usuario_id' => $usuario->id_usuario,
            'programa_destino_id' => $request->input('programa_destino'),
            'programa_origen_id' => $request->input('programa_origen'),
            'finalizo_estudios' => $request->input('finalizo_estudios'),
            'fecha_finalizacion_estudios' => $request->filled('fecha_finalizacion') ? $request->input('fecha_finalizacion') : null,
            'fecha_ultimo_semestre_cursado' => $request->filled('fecha_ultimo_semestre') ? $request->input('fecha_ultimo_semestre') : null,
            'estado' => 'Radicado',
            'numero_radicado' => $request->input('numero_rad'),
            'fecha_solicitud' => now(),
            'ruta_pdf_resolucion' => null,
        ]);

        // 3. Guardar materias con detalles completos
        SolicitudAsignatura::create([
            'solicitud_id' => $solicitud->id_solicitud,
            'asignaturas' => $materiasJson,
            'nombre' => 'Todas las materias',
            'nota' => null,
            'semestre' => null
        ]);

        // 4. Procesar documentos con estructura de directorios
        if ($request->hasFile('documentos') && $request->has('tipos') && $request->has('directorios')) {
            $documentos = $request->file('documentos');
            $tipos = $request->input('tipos');
            $directorios = $request->input('directorios');

            foreach ($documentos as $index => $archivo) {
                if ($archivo->isValid()) {
                    // Determinar directorio de destino
                    $dirDestino = $directorios[$index] ?? 'documentos';

                    // Guardar archivo en el directorio correcto
                    $ruta = $archivo->store($dirDestino, 'public');

                    // Crear registro de documento
                    Documento::create([
                        'usuario_id' => $usuario->id_usuario,
                        'solicitud_id' => $solicitud->id_solicitud,
                        'ruta' => $ruta,
                        'tipo' => $tipos[$index] ?? 'Documento',
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'message' => 'Solicitud registrada correctamente',
            'solicitud_id' => $solicitud->id_solicitud,
            'numero_radicado' => $solicitud->numero_radicado
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();

        // Registrar error en logs
        Log::error('Error en SolicitudCompletaControllerApi@update: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->except(['password'])
        ]);

        return response()->json([
            'error' => 'Error al procesar la solicitud',
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Sanitiza una cadena JSON para eliminar caracteres problemáticos
     *
     * @param string $json
     * @return string
     */
    private function sanitizeJsonString($json)
    {
        // Eliminar BOM y otros caracteres invisibles al inicio
        $json = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $json);

        // Obtener solo caracteres válidos para JSON
        $json = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $json);

        // No usamos str_replace aquí pues puede haber secuencias inválidas de escape

        // Intentar detectar si el JSON empieza y termina correctamente
        $trimmed = trim($json);
        $firstChar = substr($trimmed, 0, 1);
        $lastChar = substr($trimmed, -1);

        // Verificar que comienza con { o [ y termina con } o ]
        if (($firstChar !== '{' && $firstChar !== '[') || ($lastChar !== '}' && $lastChar !== ']')) {
            // Buscar el primer { o [ y el último } o ]
            $openBrace = strpos($trimmed, '{');
            $openBracket = strpos($trimmed, '[');
            $closeBrace = strrpos($trimmed, '}');
            $closeBracket = strrpos($trimmed, ']');

            // Encontrar el primer carácter de apertura
            $start = false;
            if ($openBrace !== false && ($openBracket === false || $openBrace < $openBracket)) {
                $start = $openBrace;
            } elseif ($openBracket !== false) {
                $start = $openBracket;
            }

            // Encontrar el último carácter de cierre
            $end = false;
            if ($closeBrace !== false && ($closeBracket === false || $closeBrace > $closeBracket)) {
                $end = $closeBrace + 1; // +1 para incluir el carácter de cierre
            } elseif ($closeBracket !== false) {
                $end = $closeBracket + 1; // +1 para incluir el carácter de cierre
            }

            // Si encontramos un inicio y un fin válidos, extraer esa parte
            if ($start !== false && $end !== false && $start < $end) {
                $json = substr($trimmed, $start, $end - $start);
            }
        }

        return $json;
    }
}
