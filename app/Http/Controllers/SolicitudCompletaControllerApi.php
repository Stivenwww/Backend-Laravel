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
    public function store(Request $request)
    {
        // Validación básica
        $validator = Validator::make($request->all(), [
            'tipo_identificacion' => 'required|string|max:30',
            'numero_identificacion' => 'required|string|max:20',
            'primer_nombre' => 'required|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string',
            'pais' => 'required|string|max:50',
            'departamento' => 'required',
            'municipio' => 'required',
            'programa' => 'required',
            'finalizo_estudios' => 'required|string|in:si,no',
            'numero_rad' => 'required|string',
            'password' => 'required|string|min:6',
            'materias' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Error de validación',
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Verificar y procesar el campo materias
            $materiasJson = $request->input('materias');

            // Si materias es un string, intentamos decodificarlo
            if (is_string($materiasJson)) {
                // Limpieza básica para problemas comunes
                $materiasJson = $this->sanitizeJsonString($materiasJson);

                // Decodificar JSON
                $materiasPorSemestre = json_decode($materiasJson, true);

                // Verificar si la decodificación fue exitosa
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'error' => 'Error de validación',
                        'message' => 'El formato JSON de materias no es válido: ' . json_last_error_msg(),
                        'received_data' => substr($materiasJson, 0, 500) // Primeros 500 caracteres
                    ], 422);
                }
            } else {
                // Si ya es un array (por ejemplo, si Laravel ya lo decodificó automáticamente)
                $materiasPorSemestre = $materiasJson;
            }

            // Verificar que materiasPorSemestre sea un array y no esté vacío
            if (!is_array($materiasPorSemestre) || empty($materiasPorSemestre)) {
                return response()->json([
                    'error' => 'Error de validación',
                    'message' => 'No se proporcionaron materias para homologar'
                ], 422);
            }

            // 1. Crear usuario
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
            ]);

            // 2. Crear solicitud
            $solicitud = Solicitud::create([
                'usuario_id' => $usuario->id_usuario,
                'programa_destino_id' => $request->input('programa'),
                'finalizo_estudios' => $request->input('finalizo_estudios'),
                'fecha_finalizacion_estudios' => $request->filled('fecha_finalizacion') ? $request->input('fecha_finalizacion') : null,
                'fecha_ultimo_semestre_cursado' => $request->filled('fecha_ultimo_semestre') ? $request->input('fecha_ultimo_semestre') : null,
                'estado' => 'Radicado',
                'numero_radicado' => $request->input('numero_rad'),
                'fecha_solicitud' => now(),
                'ruta_pdf_resolucion' => null,
            ]);

            // 3. NUEVO ENFOQUE: Guardar todas las materias en un solo registro
            // Preparar el array para almacenar todas las materias
            $todasLasMaterias = [];

            foreach ($materiasPorSemestre as $semestre => $materias) {
                if (is_array($materias)) {
                    foreach ($materias as $materia) {
                        if (!isset($materia['nombre']) || !isset($materia['nota'])) {
                            continue; // Saltamos materias que no tengan nombre o nota
                        }

                        // Normalizar la nota (reemplazar coma por punto)
                        $nota = isset($materia['nota']) ? str_replace(',', '.', $materia['nota']) : null;

                        // Agregar esta materia al array general con su información de semestre
                        $todasLasMaterias[] = [
                            'nombre' => $materia['nombre'],
                            'nota' => $nota,
                            'semestre' => $semestre,
                            'asignaturas' => isset($materia['asignaturas']) ? $materia['asignaturas'] : []
                        ];
                    }
                }
            }

            // Crear un único registro en la tabla solicitud_asignaturas
            SolicitudAsignatura::create([

                'solicitud_id' => $solicitud->id_solicitud,
                'asignaturas' => json_encode($todasLasMaterias),
                'nombre' => 'Todas las materias', // Un nombre genérico
                'nota' => null, // No aplicable para el conjunto
                'semestre' => null // No aplicable para el conjunto
            ]);

            // 4. Procesar documentos (si los hubiera)
            if ($request->hasFile('documentos') && $request->has('tipos')) {
                $documentos = $request->file('documentos');
                $tipos = $request->input('tipos');

                foreach ($documentos as $index => $archivo) {
                    if ($archivo->isValid()) {
                        $ruta = $archivo->store('solicitudes', 'public');

                        Documento::create([
                            'usuario_id' => $usuario->id_usuario,
                            'solicitud_id' => $solicitud->id_solicitud,
                            'ruta' => $ruta,
                            'tipo' => $tipos[$index] ?? 'Documento de Identidad', // Valor por defecto si falta
                        ]);
                    }
                }
            }


            DB::commit();

            return response()->json([
                'message' => 'Solicitud registrada correctamente',
                'solicitud_id' => $solicitud->id_solicitud,
                'numero_radicado' => $solicitud->numero_radicado
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Registrar error en los logs
            Log::error('Error en SolicitudCompletaControllerApi@store: ' . $e->getMessage(), [
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
