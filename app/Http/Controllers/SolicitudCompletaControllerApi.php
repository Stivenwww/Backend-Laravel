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
    // Modify the update method in SolicitudCompletaControllerApi
    public function update(Request $request)
{
    // Inicio de log para diagnóstico
    Log::info('Iniciando SolicitudCompletaControllerApi@update');
    Log::info('Request recibido:', ['data' => $request->except(['password', 'documentos'])]);

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
        'departamento' => 'nullable',
        'municipio' => 'nullable',
        'programa_origen' => 'present',
        'programa_destino' => 'required',
        'finalizo_estudios' => 'required|string',
        'numero_rad' => 'required|string',
        'password' => 'required|string|min:6',
        'materias' => 'required'
    ]);

    if ($validator->fails()) {
        Log::warning('Validación fallida:', $validator->errors()->toArray());
        return response()->json([
            'error' => true,
            'message' => 'Error de validación: ' . $validator->errors()->first(),
            'all_errors' => $validator->errors()->toArray()
        ], 422);
    }

    // Utilizamos un try-catch más robusto para garantizar siempre una respuesta
    try {
        DB::beginTransaction();
        Log::info('Transacción iniciada');

        // 1. Procesar usuario
        try {
            Log::info('Buscando usuario por email: ' . $request->input('email'));
            $usuario = User::where('email', $request->input('email'))->first();

            // Preparar datos de usuario
            $userData = [
                'tipo_identificacion' => $request->input('tipo_identificacion'),
                'numero_identificacion' => $request->input('numero_identificacion'),
                'primer_nombre' => $request->input('primer_nombre'),
                'segundo_nombre' => $request->input('segundo_nombre') ?: null,
                'primer_apellido' => $request->input('primer_apellido'),
                'segundo_apellido' => $request->input('segundo_apellido') ?: null,
                'telefono' => $request->input('telefono'),
                'direccion' => $request->input('direccion'),
            ];

            // Datos geográficos
            if ($request->filled('pais')) {
                $userData['pais_id'] = is_numeric($request->input('pais')) ? (int) $request->input('pais') : null;
            } else {
                $userData['pais_id'] = null;
            }

            $departamento = $request->input('departamento');
            if (!empty($departamento) && is_numeric($departamento)) {
                $userData['departamento_id'] = (int) $departamento;
            } else {
                $userData['departamento_id'] = null;
            }

            $municipio = $request->input('municipio');
            if (!empty($municipio) && is_numeric($municipio)) {
                $userData['municipio_id'] = (int) $municipio;
            } else {
                $userData['municipio_id'] = null;
            }

            // Datos de institución
            if ($request->filled('institucion_origen')) {
                $institucionOrigen = $request->input('institucion_origen');
                if (is_numeric($institucionOrigen)) {
                    $userData['institucion_origen_id'] = (int) $institucionOrigen;
                } else {
                    $institucionNombre = trim($request->input('institucion_origen_nombre'));
                    if (!empty($institucionNombre)) {
                        $institucion = DB::table('instituciones')
                            ->where('nombre', 'like', '%' . $institucionNombre . '%')
                            ->first();
                        if ($institucion) {
                            $userData['institucion_origen_id'] = $institucion->id_institucion;
                        }
                    }
                }
            }

            if ($request->filled('tipo_formacion')) {
                $userData['facultad'] = $request->input('tipo_formacion');
            }

            // Actualizar o crear usuario
            if ($usuario) {
                $usuario->update($userData);
                Log::info('Usuario actualizado: ' . $usuario->id_usuario);
            } else {
                $userData['email'] = $request->input('email');
                $userData['password'] = bcrypt($request->input('password'));
                $usuario = User::create($userData);
                Log::info('Usuario creado: ' . $usuario->id_usuario);
            }
        } catch (\Exception $e) {
            throw new \Exception('Error al procesar usuario: ' . $e->getMessage(), 0, $e);
        }

        // 2. Crear solicitud
        try {
            Log::info('Creando solicitud para usuario: ' . $usuario->id_usuario);
            $solicitud = Solicitud::create([
                'usuario_id' => $usuario->id_usuario,
                'programa_destino_id' => $request->input('programa_destino'),
                'programa_origen_id' => $request->filled('programa_origen') && is_numeric($request->input('programa_origen')) ?
                    (int) $request->input('programa_origen') : null,
                'finalizo_estudios' => $request->input('finalizo_estudios'),
                'fecha_finalizacion_estudios' => $request->filled('fecha_finalizacion') ? $request->input('fecha_finalizacion') : null,
                'fecha_ultimo_semestre_cursado' => $request->filled('fecha_ultimo_semestre') ? $request->input('fecha_ultimo_semestre') : null,
                'estado' => 'Radicado',
                'numero_radicado' => $request->input('numero_rad'),
                'fecha_solicitud' => now(),
                'ruta_pdf_resolucion' => null,
            ]);
            Log::info('Solicitud creada con ID: ' . $solicitud->id_solicitud);
        } catch (\Exception $e) {
            throw new \Exception('Error al crear solicitud: ' . $e->getMessage(), 0, $e);
        }

        // 3. Procesar materias
        try {
            Log::info('Procesando materias');
            $materiasJson = $request->input('materias');

            if ($request->input('materias_is_json') === 'true') {
                // Si viene con el indicador de que ya es JSON, usar directamente
                Log::info('Materias llegaron como JSON: ' . substr($materiasJson, 0, 100) . '...');

                // Asegurar que es un JSON válido
                if (!is_string($materiasJson) || substr($materiasJson, 0, 1) !== '[' || substr($materiasJson, -1) !== ']') {
                    Log::warning('El formato JSON recibido no es el esperado, aplicando sanitización');
                    $materiasJson = $this->sanitizeJsonString($materiasJson);
                }

                // Verificar que el JSON es válido
                $testDecode = json_decode($materiasJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('JSON recibido inválido: ' . json_last_error_msg());
                }
            } else {
                // Procesar como en versiones anteriores
                if (is_string($materiasJson)) {
                    $materiasJson = $this->sanitizeJsonString($materiasJson);
                }

                $materias = json_decode($materiasJson, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Error al decodificar JSON de materias: ' . json_last_error_msg());
                }

                if (!is_array($materias) || empty($materias)) {
                    throw new \Exception('No se proporcionaron materias para homologar');
                }

                $materiasSimplificadas = array_map(function ($materia) {
                    return [
                        'asignatura_id' => $materia['asignatura_id'] ?? 0,
                        'nota_origen' => $materia['nota_origen'] ?? 0,
                        'horas_sena' => $materia['horas_sena'] ?? null
                    ];
                }, $materias);

                $materiasJson = json_encode($materiasSimplificadas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            // Crear la relación solicitud-asignaturas
            $solicitudAsignatura = SolicitudAsignatura::create([
                'solicitud_id' => $solicitud->id_solicitud,
                'asignaturas' => $materiasJson,
                'nombre' => 'Todas las materias',
                'nota' => null,
                'semestre' => null
            ]);
            Log::info('SolicitudAsignatura creada con ID: ' . $solicitudAsignatura->id_solicitud_asignatura);
        } catch (\Exception $e) {
            throw new \Exception('Error al procesar materias: ' . $e->getMessage(), 0, $e);
        }

        // 4. Procesar documentos
        try {
            if ($request->hasFile('documentos') && $request->has('tipos') && $request->has('directorios')) {
                Log::info('Procesando documentos adjuntos');
                $documentos = $request->file('documentos');
                $tipos = $request->input('tipos');
                $directorios = $request->input('directorios');

                foreach ($documentos as $index => $archivo) {
                    if ($archivo->isValid()) {
                        $dirDestino = $directorios[$index] ?? 'documentos';
                        $nombreArchivo = $archivo->getClientOriginalName();
                        Log::info("Guardando archivo: {$nombreArchivo} en directorio: {$dirDestino}");

                        $ruta = $archivo->store($dirDestino, 'public');

                        Documento::create([
                            'usuario_id' => $usuario->id_usuario,
                            'solicitud_id' => $solicitud->id_solicitud,
                            'ruta' => $ruta,
                            'tipo' => $tipos[$index] ?? 'Documento',
                        ]);
                    } else {
                        Log::warning("Archivo no válido en índice {$index}");
                    }
                }
            } else {
                Log::info('No se recibieron documentos para procesar');
            }
        } catch (\Exception $e) {
            throw new \Exception('Error al procesar documentos: ' . $e->getMessage(), 0, $e);
        }

        // Confirmar transacción
        DB::commit();
        Log::info('Transacción completada con éxito');

        // Devolver respuesta exitosa
        return response()->json([
            'message' => 'Solicitud registrada correctamente',
            'solicitud_id' => $solicitud->id_solicitud,
            'numero_radicado' => $solicitud->numero_radicado
        ], 200);

    } catch (\Exception $e) {
        // Asegurar rollback de transacción
        try {
            DB::rollBack();
            Log::error('Transacción revertida');
        } catch (\Exception $rollbackError) {
            Log::error('Error adicional al hacer rollback: ' . $rollbackError->getMessage());
        }

        // Registrar error completo
        Log::error('Error en SolicitudCompletaControllerApi@update: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        // Siempre devolver una respuesta JSON válida
        return response()->json([
            'error' => true,
            'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            'error_details' => [
                'file' => basename($e->getFile()),
                'line' => $e->getLine()
            ]
        ], 500);
    }
}

    //secoo

    /**
     * Sanitiza una cadena JSON para eliminar caracteres problemáticos
     *
     * @param string $json
     * @return string
     */
    private function sanitizeJsonString($json)
{
    // Si ya es un array, no necesita sanitización
    if (is_array($json)) {
        return json_encode($json);
    }

    // Eliminar BOM y otros caracteres invisibles al inicio
    $json = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $json);

    // Obtener solo caracteres válidos para JSON
    $json = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '', $json);

    // Verificar si el string ya contiene barras invertidas escapadas
    if (strpos($json, '\\"') !== false) {
        // Está doblemente escapado, intenta desescarpar
        $json = stripslashes($json);
    }

    // Verificar que comienza con { o [ y termina con } o ]
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

    // Verificar que el JSON es válido antes de retornarlo
    $testDecode = json_decode($json);
    if (json_last_error() !== JSON_ERROR_NONE) {
        // Si no es JSON válido, intenta repararlo
        return '[]'; // Retornar un array vacío como fallback
    }

    return $json;
}
}
