<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Solicitud;
use App\Models\SolicitudAsignatura;
use App\Models\HomologacionAsignatura;
use App\Models\Asignatura;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Obtener todas las solicitudes que tienen materias registradas
        $solicitudesAsignaturas = SolicitudAsignatura::all();

        foreach ($solicitudesAsignaturas as $solicitudAsignatura) {
            $solicitudId = $solicitudAsignatura->solicitud_id;
            $solicitud = Solicitud::find($solicitudId);

            // Array para almacenar las homologaciones
            $homologacionesData = [];

            // Procesar cada asignatura de origen en el array JSON
            $asignaturasOrigen = $solicitudAsignatura->asignaturas;
            foreach ($asignaturasOrigen as $index => $asignaturaOrigen) {
                // Agregar homologación al array (sólo con las asignaturas origen, destino null)
                $homologacionesData[] = [
                    'asignatura_origen_id' => $asignaturaOrigen['asignatura_id'],
                    'asignatura_destino_id' => null, // Asignatura destino establecida a NULL
                    'nota_destino' => null,
                    'comentarios' => null // Comentarios establecidos a NULL
                ];
            }

            // Crear un único registro para esta solicitud con todas sus homologaciones
            HomologacionAsignatura::create([
                'solicitud_id' => $solicitudId,
                'homologaciones' => $homologacionesData,
                'fecha' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
