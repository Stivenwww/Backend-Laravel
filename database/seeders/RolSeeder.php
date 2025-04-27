<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rol::insert([
            ['nombre' => 'Aspirante'],
            ['nombre' => 'Coordinador'],
            ['nombre' => 'Decano'],
            ['nombre' => 'Vicerrector'],
            ['nombre' => 'Administrador']
        ]);
    }
}
