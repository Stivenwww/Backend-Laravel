<?php

namespace Database\Seeders;

use App\Models\HomologacionAsignatura;
use App\Models\SolicitudAsignatura;
use App\Models\Solicitud;
use App\Models\Asignatura;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomologacionAsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar nuevos datos
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        HomologacionAsignatura::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Obtener todas las asignaturas del programa destino (Ing. Software - Autónoma del Cauca)
        $asignaturasDestino = Asignatura::where('programa_id', 12)->get();

        // Verificar si hay asignaturas destino disponibles
        if ($asignaturasDestino->isEmpty()) {
            echo "No hay asignaturas destino (programa_id = 12) disponibles. Abortando.\n";
            return;
        }

        // Procesar las solicitudes de los 6 usuarios (asegurándose de incluir todos)
        for ($usuarioId = 1; $usuarioId <= 6; $usuarioId++) {
            // Buscar la solicitud para este usuario
            $solicitud = Solicitud::where('usuario_id', $usuarioId)->first();

            if (!$solicitud) {
                echo "Solicitud para usuario $usuarioId no encontrada. Saltando.\n";
                continue;
            }

            $solicitudId = $solicitud->id_solicitud;
            echo "Procesando solicitud #$solicitudId para usuario #$usuarioId\n";

            // Obtener el usuario para determinar su institución
            $usuario = User::find($usuarioId);
            if (!$usuario) {
                echo "Usuario $usuarioId no encontrado. Saltando.\n";
                continue;
            }

            // Determinar si es estudiante SENA
            $esSena = $usuario->institucion_origen_id == 2;

            // Obtener asignaturas vinculadas a esta solicitud
            $asignaturasVinculadas = SolicitudAsignatura::where('solicitud_id', $solicitudId)->get();

            if ($asignaturasVinculadas->isEmpty()) {
                echo "No hay asignaturas vinculadas a la solicitud $solicitudId. Saltando.\n";
                continue;
            }

            echo "Encontradas " . $asignaturasVinculadas->count() . " asignaturas vinculadas para solicitud $solicitudId\n";

            // Obtener IDs de asignaturas vinculadas
            $asignaturaIds = $asignaturasVinculadas->pluck('asignatura_id')->toArray();

            // Obtener datos completos de las asignaturas de origen
            $asignaturasOrigen = Asignatura::whereIn('id_asignatura', $asignaturaIds)
                ->where('programa_id', '!=', 12) // Asegurarse que no sean del programa destino
                ->get();

            // Manejo especial para el usuario 1 si no tiene asignaturas de origen válidas
            if ($asignaturasOrigen->isEmpty()) {
                echo "No hay asignaturas de origen válidas para la solicitud $solicitudId, intentando obtener otras asignaturas de origen...\n";

                // Obtener asignaturas de origen aleatorias que no sean del programa destino
                $asignaturasOrigen = Asignatura::where('programa_id', '!=', 12)
                    ->inRandomOrder()
                    ->take(6)
                    ->get();

                if ($asignaturasOrigen->isEmpty()) {
                    echo "No se pudieron encontrar asignaturas de origen alternativas. Saltando usuario $usuarioId.\n";
                    continue;
                }

                echo "Se encontraron " . $asignaturasOrigen->count() . " asignaturas de origen alternativas.\n";
            } else {
                echo "Encontradas " . $asignaturasOrigen->count() . " asignaturas de origen válidas\n";
            }

            // Usar todas las asignaturas de origen (hasta 6)
            $cantidadHomologar = min($asignaturasOrigen->count(), 6);

            // Tomar solo la cantidad necesaria
            $origenSeleccionadas = $asignaturasOrigen->take($cantidadHomologar);

            // Seleccionar asignaturas destino aleatorias (sin repetir)
            $destinoSeleccionadas = $asignaturasDestino->random($cantidadHomologar);

            // Crear homologaciones
            for ($i = 0; $i < $cantidadHomologar; $i++) {
                $origen = $origenSeleccionadas[$i];
                $destino = $destinoSeleccionadas[$i];

                // Si es del SENA, algunas homologaciones pueden no tener nota
                $asignarNota = !$esSena || rand(0, 1) == 1;

                HomologacionAsignatura::create([
                    'solicitud_id'          => $solicitudId,
                    'asignatura_origen_id'  => $origen->id_asignatura,
                    'asignatura_destino_id' => $destino->id_asignatura,
                    'nota_destino'          => $asignarNota ? $this->generarNotaDestino() : null,
                    'comentarios'           => $this->generarComentario($esSena),
                    'fecha'                 => now()->subDays(rand(1, 30)),
                ]);

                echo "Creada homologación para solicitud $solicitudId: {$origen->nombre} → {$destino->nombre}\n";
            }

            echo "Completadas $cantidadHomologar homologaciones para usuario $usuarioId\n";
        }
    }

    /**
     * Genera una nota aleatoria para la asignatura de destino entre 3.8 y 5.0
     */
    private function generarNotaDestino()
    {
        return round(mt_rand(38, 50) / 10, 1);
    }

    /**
     * Genera un comentario aleatorio para la homologación
     */
    private function generarComentario($esSena = false)
    {
        $comentariosSena = [
            'La competencia SENA cubre el 85% de los contenidos de la asignatura.',
            'Se recomienda reforzar componentes teóricos no cubiertos en el SENA.',
            'Las horas de formación SENA son equivalentes al trabajo académico de la asignatura.',
            'Evidencias de aprendizaje SENA satisfactorias para la homologación.',
            'Requiere complementar con taller adicional sobre temas avanzados.',
            'Homologación parcial, pendiente validación de conceptos teóricos.',
            'La formación práctica del SENA es suficiente para esta asignatura.',
            'Instructivo SENA cumple con los resultados de aprendizaje de la asignatura destino.',
        ];

        $comentariosUniversidad = [
            'Contenidos programáticos coincidentes en un 90%.',
            'Syllabus y evaluación de competencias compatible con la asignatura destino.',
            'Buena correspondencia en resultados de aprendizaje.',
            'Créditos académicos equivalentes con intensidad horaria similar.',
            'Metodologías de evaluación compatibles con nuestro programa.',
            'Nivel de profundidad adecuado según revisión del syllabus.',
            'Los proyectos prácticos desarrollados cumplen con nuestros estándares.',
            'Bibliografía y contenidos actualizados según nuestros requisitos.',
            'Evaluación satisfactoria de conocimientos previos requeridos.',
            'Prácticas de laboratorio equivalentes a nuestra asignatura.',
        ];

        $comentarios = $esSena ? $comentariosSena : $comentariosUniversidad;

        return $comentarios[array_rand($comentarios)];
    }
}
