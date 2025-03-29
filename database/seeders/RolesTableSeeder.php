<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roles::create([
            'nombre' => 'Aspirante'
        ]);
        Roles::create([
            'nombre' => 'Admin'
        ]);
        Roles::create([
            'nombre' => 'Coordinador'
        ]);
    }
}
