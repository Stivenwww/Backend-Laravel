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
        Schema::create('homologacion_asignaturas', function (Blueprint $table) {
            $table->smallIncrements('id_homologacion');
            $table->unsignedSmallInteger('solicitud_id');
            $table->unsignedSmallInteger('asignatura_origen_id');
            $table->unsignedSmallInteger('asignatura_destino_id')->nullable();
            $table->decimal('nota_destino', 3, 1)->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->text('comentarios')->nullable();
            $table->timestamps();

            $table->foreign('solicitud_id')->references('id_solicitud')->on('solicitudes');
            $table->foreign('asignatura_origen_id')->references('id_asignatura')->on('asignaturas');
            $table->foreign('asignatura_destino_id')->references('id_asignatura')->on('asignaturas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homologacion_asignaturas');
    }
};
