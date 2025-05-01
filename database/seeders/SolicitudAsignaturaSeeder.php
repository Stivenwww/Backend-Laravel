<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Solicitud;
use App\Models\Asignatura;
use App\Models\SolicitudAsignatura;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SolicitudAsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar nuevos datos
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SolicitudAsignatura::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $solicitudes = Solicitud::whereIn('usuario_id', [1, 2, 3, 4, 5, 6])->get();

        foreach ($solicitudes as $solicitud) {
            // Obtener el usuario de la solicitud para determinar su institución de origen
            $usuario = User::find($solicitud->usuario_id);
            $institucionOrigenId = $usuario->institucion_origen_id;

            // Determinar programa_id según la institución de origen
            $programaOrigenId = $this->obtenerProgramaOrigen($institucionOrigenId, $usuario->facultad_id);

            // Array para almacenar todas las asignaturas para esta solicitud
            $asignaturasData = [];

            // Obtener asignaturas de origen según institución
            if ($institucionOrigenId == 2) { // SENA (usuario 6)
                // Para SENA, usar competencias (programa_id entre 16-23)
                $asignaturasOrigen = Asignatura::whereBetween('programa_id', [16, 23])
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();

                // Preparar datos de asignaturas de origen del SENA (competencias)
                foreach ($asignaturasOrigen as $asignatura) {
                    $asignaturasData[] = [
                        'asignatura_id'  => $asignatura->id_asignatura,
                        'nota_origen'    => null,
                        'horas_sena'     => $asignatura->horas_sena
                    ];
                }
            } else {
                // Para universidades, usar materias del programa correspondiente
                $asignaturasOrigen = Asignatura::where('programa_id', $programaOrigenId)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();

                // Preparar datos de asignaturas de origen universitarias (con notas)
                foreach ($asignaturasOrigen as $asignatura) {
                    $asignaturasData[] = [
                        'asignatura_id'  => $asignatura->id_asignatura,
                        'nota_origen'    => $this->generarNotaAprobatoria(), // Nota superior a 3.5
                        'horas_sena'     => null
                    ];
                }
            }

            // Crear un único registro para esta solicitud con todas sus asignaturas
            SolicitudAsignatura::create([
                'solicitud_id' => $solicitud->id_solicitud,
                'asignaturas'  => $asignaturasData,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }

    /**
     * Obtiene el ID del programa correspondiente según la institución y facultad
     */
    private function obtenerProgramaOrigen($institucionId, $facultadId)
    {
        // Mapeo de institución y facultad a programa_id
        $programas = [
            // Autónoma del Cauca (1) - Depende de la facultad
            1 => [
                5 => 12, // Facultad de Ingeniería (5) -> Ing. de Software (12)
            ],
            // FUP (4) - Mapeo de facultades a programas
            4 => [
                3 => 6, // Ing. y Arquitectura (3) -> Ing. de Sistemas (6)
            ],
            // Colegio Mayor del Cauca (3)
            3 => [
                4 => 9, // Ciencias Empresariales (4) -> Ing. Informática (9)
            ],
            // UniCauca (5)
            5 => [
                2 => 1, // Ing. Electrónica y Telecomunicaciones (2) -> Mismo programa (1)
            ],
            // SENA (2) - Vamos a usar programa 16 como default
            2 => [
                null => 16, // Sin facultad -> Tecnólogo en Análisis y Desarrollo de Software (16)
            ],
        ];

        // Si no existe el mapeo específico, retornar un valor por defecto según la institución
        if (!isset($programas[$institucionId][$facultadId])) {
            // Valores por defecto para cada institución
            $defaults = [
                1 => 12, // Autónoma -> Ing. Software
                2 => 16, // SENA -> Tecnólogo en Análisis y Desarrollo
                3 => 9,  // Colegio Mayor -> Ing. Informática
                4 => 6,  // FUP -> Ing. de Sistemas
                5 => 3,  // UniCauca -> Ing. de Sistemas
            ];

            return $defaults[$institucionId] ?? 3; // Por defecto Ing. de Sistemas en UniCauca
        }

        return $programas[$institucionId][$facultadId];
    }

    /**
     * Genera una nota aprobatoria aleatoria entre 3.5 y 5.0
     */
    private function generarNotaAprobatoria()
    {
        // Generar nota entre 3.5 y 5.0 con un decimal
        return round(mt_rand(35, 50) / 10, 1);
    }
}
