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
        Schema::create('historial_homologaciones', function (Blueprint $table) {
            $table->smallIncrements('id_historial');
            $table->unsignedSmallInteger('solicitud_id');
            $table->unsignedSmallInteger('usuario_id');
            $table->enum('estado', ['Radicado', 'En revisión', 'Evaluado', 'Aprobado', 'Rechazado', 'Cerrado']);
            $table->timestamp('fecha')->useCurrent();
            $table->text('observaciones')->nullable();
            $table->string('ruta_pdf_resolucion', 255)->nullable();
            $table->timestamps();

            $table->foreign('solicitud_id')->references('id_solicitud')->on('solicitudes')->onDelete('cascade');
         $table->foreign('usuario_id')->references('id_usuario')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_homologaciones');
    }
};
