<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institucion;
use Exception;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/instituciones.csv"), "r");

        $firstline = true;
        $insertados = 0;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                try {
                    Institucion::create([
                        'codigo_ies' => $data[0],
                        'nombre' => $data[1],
                        'municipio_id' => (int)$data[3],  // ← Era $data[4]
                        'tipo' => trim($data[2])          // ← Era $data[3]
                    ]);
                    $insertados++;
                } catch (Exception $e) {
                    dump("Error en registro:", $data[0], $data[1], $e->getMessage());
                }
            }
            $firstline = false;
        }

        fclose($csvFile);
        dump("Total insertados:", $insertados);
    }
}
