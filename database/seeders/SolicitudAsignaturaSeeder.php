<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Solicitud;
use App\Models\Asignatura;
use App\Models\SolicitudAsignatura;

class SolicitudAsignaturaSeeder extends Seeder
{
    public function run(): void
    {
        $solicitudes = Solicitud::all();

        foreach ($solicitudes as $solicitud) {
            // 2 asignaturas de origen (SENA: programa_id 16–23)
            $asignaturasOrigen = Asignatura::whereBetween('programa_id', [16, 23])
                ->inRandomOrder()
                ->limit(2)
                ->get();

            // 2 asignaturas de destino (Ingeniería de Software: programa_id = 12)
            $asignaturasDestino = Asignatura::where('programa_id', 12)
                ->inRandomOrder()
                ->limit(2)
                ->get();

            // Para cada SENA: guardamos horas_sena, nota_origen = null
            foreach ($asignaturasOrigen as $asigSena) {
                SolicitudAsignatura::create([
                    'solicitud_id'   => $solicitud->id_solicitud,
                    'asignatura_id'  => $asigSena->id_asignatura,
                    'nota_origen'    => null,
                    'horas_sena'     => $asigSena->horas_sena,
                ]);
            }

            // Para cada no-SENA: guardamos nota_origen, horas_sena = null
            foreach ($asignaturasDestino as $asigDestino) {
                SolicitudAsignatura::create([
                    'solicitud_id'   => $solicitud->id_solicitud,
                    'asignatura_id'  => $asigDestino->id_asignatura,
                    'nota_origen'    => rand(30, 50) / 10, // 3.0 – 5.0
                    'horas_sena'     => null,
                ]);
            }
        }
    }
}
