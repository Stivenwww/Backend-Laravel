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
            $table->unsignedSmallInteger('solicitud_id')->unique(); // Hacemos único para asegurar un registro por solicitud
            $table->json('homologaciones'); // Guardará un array con la estructura {asignatura_origen_id, asignatura_destino_id, nota_destino, comentarios}
            $table->timestamp('fecha')->useCurrent();
            $table->string('ruta_pdf_resolucion', 255)->nullable();
            $table->timestamps();

            $table->foreign('solicitud_id')->references('id_solicitud')->on('solicitudes')->onDelete('cascade');
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
