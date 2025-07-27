<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->smallIncrements('id_usuario');
            $table->string('primer_nombre', 50)->nullable();
            $table->string('segundo_nombre', 50)->nullable();
            $table->string('primer_apellido', 50)->nullable();
            $table->string('segundo_apellido', 50)->nullable();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('tipo_identificacion', ['Tarjeta de Identidad', 'Cédula de Ciudadanía', 'Cédula de Extranjería']);
            $table->string('numero_identificacion', 20)->unique()->nullable();

            $table->unsignedSmallInteger('institucion_origen_id')->nullable();
            $table->unsignedSmallInteger('facultad_id')->nullable();

            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 255)->nullable();

            $table->unsignedSmallInteger('pais_id')->nullable();
            $table->unsignedSmallInteger('departamento_id')->nullable();
            $table->unsignedSmallInteger('municipio_id')->nullable();
            $table->unsignedSmallInteger('rol_id')->default(1);

            $table->boolean('activo')->default(true);
            $table->enum('modo_visual', ['0', '1'])->default('0');
            $table->rememberToken();

            $table->foreign('pais_id')->references('id_pais')->on('paises');
            $table->foreign('departamento_id')->references('id_departamento')->on('departamentos');
            $table->foreign('municipio_id')->references('id_municipio')->on('municipios');
            $table->foreign('institucion_origen_id')->references('id_institucion')->on('instituciones');
            $table->foreign('rol_id')->references('id_rol')->on('roles');
            $table->foreign('facultad_id')->references('id_facultad')->on('facultades');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
