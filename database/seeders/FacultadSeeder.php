<?php

namespace Database\Seeders;

use App\Models\Facultad;
use Illuminate\Database\Seeder;

class FacultadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        /* Facultad de universidad del cauca */
        Facultad::create([
            'institucion_id' => 5,
            'nombre' => 'Facultad de Ingeniería Civil',
        ]);

        Facultad::create([
            'institucion_id' => 5,
            'nombre' => 'Facultad de Ingeniería Electrónica y Telecomunicaciones',
        ]);



        /* Facultades de la FUP */
        Facultad::create([
            'institucion_id' => 4,
            'nombre' => 'Facultad de Ingeniería y Arquitectura',
        ]);



        /* Colegio mayor */

        Facultad::create([
            'institucion_id' => 3,
            'nombre' => 'Facultad de Ingeniería',
        ]);


        
        /* Uniautonoma */

        Facultad::create([
            'institucion_id' => 1,
            'nombre' => 'Facultad de Ingeniería',
        ]);


    }
}
