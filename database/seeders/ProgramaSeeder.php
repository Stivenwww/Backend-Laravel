<?php

namespace Database\Seeders;

use App\Models\Programa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Universidad del Cauca
        Programa::create([
            'institucion_id' => 5,
            'facultad_id' => 2, // Facultad de Ingeniería Electrónica y Telecomunicaciones
            'nombre' => 'Ingeniería Electrónica y Telecomunicaciones',
            'codigo_snies' => '3104',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 5,
            'facultad_id' => 1, // Facultad de Ingeniería Civil
            'nombre' => 'Ingeniería Civil',
            'codigo_snies' => '1105',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 5,
            'facultad_id' => 2,
            'nombre' => 'Ingeniería de Sistemas',
            'codigo_snies' => '1050',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 5,
            'facultad_id' => 2,
            'nombre' => 'Ingeniería en Automática Industrial',
            'codigo_snies' => '1106',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 5,
            'facultad_id' => 2,
            'nombre' => 'Ingeniería Física',
            'codigo_snies' => '1107',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);



        // Fundación Universitaria de Popayán (FUP)
        Programa::create([
            'institucion_id' => 4,
            'facultad_id' => 3, // Facultad de Ingeniería y Arquitectura
            'nombre' => 'Ingeniería de Sistemas',
            'codigo_snies' => '2612',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 4,
            'facultad_id' => 3,
            'nombre' => 'Ingeniería Industrial',
            'codigo_snies' => '2555',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 4,
            'facultad_id' => 3,
            'nombre' => 'Arquitectura',
            'codigo_snies' => '3615',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);



        // Institución Universitaria Colegio Mayor del Cauca (Unimayor)
        Programa::create([
            'institucion_id' => 3,
            'facultad_id' => 4, // Facultad de Ingeniería
            'nombre' => 'Ingeniería Informática',
            'codigo_snies' => '106716',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 3,
            'facultad_id' => 4,
            'nombre' => 'Ingeniería Electrónica',
            'codigo_snies' => '54559',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 3,
            'facultad_id' => 4,
            'nombre' => 'Tecnología en Desarrollo de Software',
            'codigo_snies' => '1108',
            'tipo_formacion' => 'Tecnólogo',
            'metodologia' => 'Presencial',
        ]);



        // Corporación Universitaria Autónoma del Cauca (Uniautónoma)
        Programa::create([
            'institucion_id' => 1,
            'facultad_id' => 5, // Facultad de Ingeniería
            'nombre' => 'Ingeniería de Software y Computación',
            'codigo_snies' => '110398',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 1,
            'facultad_id' => 5,
            'nombre' => 'Ingeniería Electrónica',
            'codigo_snies' => '20415',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 1,
            'facultad_id' => 5,
            'nombre' => 'Ingeniería Civil',
            'codigo_snies' => '111155',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 1,
            'facultad_id' => 5,
            'nombre' => 'Ingeniería Energética',
            'codigo_snies' => '110670',
            'tipo_formacion' => 'Profesional',
            'metodologia' => 'Presencial',
        ]);


        
        // SENA Regional Cauca
        Programa::create([
            'institucion_id' => 2,
            'facultad_id' => null,
            'nombre' => 'Tecnólogo en Análisis y Desarrollo de Software',
            'codigo_snies' => null,
            'tipo_formacion' => 'Tecnólogo',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 2,
            'facultad_id' => null,
            'nombre' => 'Técnico en Sistemas',
            'codigo_snies' => null,
            'tipo_formacion' => 'Técnico',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 2,
            'facultad_id' => null,
            'nombre' => 'Tecnólogo en Gestión de Redes de Datos',
            'codigo_snies' => null,
            'tipo_formacion' => 'Tecnólogo',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 2,
            'facultad_id' => null,
            'nombre' => 'Tecnólogo en Producción Multimedia',
            'codigo_snies' => null,
            'tipo_formacion' => 'Tecnólogo',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 2,
            'facultad_id' => null,
            'nombre' => 'Técnico en Programación de Software',
            'codigo_snies' => null,
            'tipo_formacion' => 'Técnico',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 2,
            'facultad_id' => null,
            'nombre' => 'Tecnólogo en Implementación de Infraestructura de Tecnologías de la Información y las Comunicaciones',
            'codigo_snies' => null,
            'tipo_formacion' => 'Tecnólogo',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 2,
            'facultad_id' => null,
            'nombre' => 'Tecnólogo en Gestión de la Seguridad y Salud en el Trabajo',
            'codigo_snies' => null,
            'tipo_formacion' => 'Tecnólogo',
            'metodologia' => 'Presencial',
        ]);

        Programa::create([
            'institucion_id' => 2,
            'facultad_id' => null,
            'nombre' => 'Tecnólogo en Gestión de Proyectos de Desarrollo de Software',
            'codigo_snies' => null,
            'tipo_formacion' => 'Tecnólogo',
            'metodologia' => 'Presencial',
        ]);

    }
}
