<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



        $this->call(PaisSeeder::class);

        $this->call(DepartamentoSeeder::class);

        $this->call(MunicipioSeeder::class);

        $this->call(InstitucionSeeder::class);

        $this->call(FacultadSeeder::class);

        $this->call(ProgramaSeeder::class);

        $this->call(RolSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(AsignaturaSeeder::class);

        $this->call(SolicitudSeeder::class);

        $this->call(DocumentoSeeder::class);

        $this->call(HistorialHomologacionSeeder::class);

        $this->call(ContenidoProgramaticoSeeder::class);

        $this->call(SolicitudAsignaturaSeeder::class);

        $this->call(HomologacionAsignaturaSeeder::class);
    }
}
