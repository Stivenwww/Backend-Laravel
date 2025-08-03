<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Solicitud;
use App\Models\Asignatura;
use App\Models\SolicitudAsignatura;
use App\Models\User;
use App\Models\Pensum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolicitudAsignaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SolicitudAsignatura::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $solicitudes = Solicitud::whereIn('usuario_id', [1, 2, 3, 4, 5, 6])->get();

        foreach ($solicitudes as $solicitud) {
            $usuario = User::find($solicitud->usuario_id);
            $institucionOrigenId = $usuario->institucion_origen_id;
            $facultadId = $usuario->facultad_id ?? null;

            try {
                $programaOrigenId = $this->obtenerProgramaOrigen($institucionOrigenId, $facultadId);
            } catch (\Exception $e) {
                // Log para que sepas quién está fallando
                Log::warning("Error en programa origen: usuario_id {$usuario->id}, institucion_id {$institucionOrigenId}, facultad_id {$facultadId}");
                continue; // Salta esta solicitud si no se puede determinar programa
            }

            $asignaturasData = [];

            if ($institucionOrigenId == 256) { // SENA
                $pensumIds = Pensum::whereBetween('programa_id', [17, 24])->pluck('id_pensum');
                $asignaturasOrigen = Asignatura::whereIn('pensum_id', $pensumIds)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();

                foreach ($asignaturasOrigen as $asignatura) {
                    $asignaturasData[] = [
                        'asignatura_id' => $asignatura->id_asignatura,
                        'nota_origen'   => 4.5,
                        'horas_sena'    => $asignatura->horas_sena,
                    ];
                }
            } else {
                $pensum = Pensum::where('programa_id', $programaOrigenId)->first();
                if ($pensum) {
                    $asignaturasOrigen = Asignatura::where('pensum_id', $pensum->id_pensum)
                        ->inRandomOrder()
                        ->limit(6)
                        ->get();

                    foreach ($asignaturasOrigen as $asignatura) {
                        $asignaturasData[] = [
                            'asignatura_id' => $asignatura->id_asignatura,
                            'nota_origen'   => $this->generarNotaAprobatoria(),
                            'horas_sena'    => null,
                        ];
                    }
                }
            }

            if (!empty($asignaturasData)) {
                SolicitudAsignatura::create([
                    'solicitud_id' => $solicitud->id_solicitud,
                    'asignaturas'  => $asignaturasData,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }
    }

    /**
     * Retorna el programa según la institución y facultad
     */
    private function obtenerProgramaOrigen($institucionId, $facultadId)
    {
        $programas = [
            149 => [ // Autónoma del Cauca
                5 => 12, // Facultad de Ingeniería → Ing. de Software
            ],
            96 => [ // FUP
                3 => 6, // Ing. y Arquitectura → Ing. de Sistemas
            ],
            158 => [ // Colegio Mayor del Cauca
                4 => 9, // Ciencias Empresariales → Ing. Informática
            ],
            4 => [ // UniCauca
                2 => 1, // Ing. Electrónica y Telecomunicaciones → mismo programa
            ],
            256 => [ // SENA
                null => 17, // Sin facultad → Tecnología en ADSO
            ],

            // 👇 Aquí puedes añadir más instituciones si es necesario:
            1 => [ // Institución de prueba
                5 => 99, // Por ejemplo: facultad 5 → programa 99 (ajusta según lo que tengas en tu DB)
            ],
        ];

        if (isset($programas[$institucionId][$facultadId])) {
            return $programas[$institucionId][$facultadId];
        }

        throw new \Exception("Programa no encontrado para institución $institucionId y facultad $facultadId");
    }

    /**
     * Genera una nota entre 3.5 y 5.0
     */
    private function generarNotaAprobatoria()
    {
        return round(mt_rand(35, 50) / 10, 1);
    }
}
