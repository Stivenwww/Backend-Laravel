<?php
// Primera migración - solicitud_asignaturas
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
        Schema::create('solicitud_asignaturas', function (Blueprint $table) {
            $table->smallIncrements('id_solicitud_asignatura');
            $table->unsignedSmallInteger('solicitud_id')->unique(); // Hacemos único para asegurar un registro por solicitud
            $table->json('asignaturas'); // Guardará un array de asignaturas con estructura {asignatura_id, nota_origen, horas_sena}
            $table->timestamps();

            $table->foreign('solicitud_id')->references('id_solicitud')->on('solicitudes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_asignaturas');
    }
};
