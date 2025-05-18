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
        Schema::create('auditoria_general', function (Blueprint $table) {
            $table->id('id_auditoria');
            $table->string('tabla_afectada', 100);
            $table->enum('tipo_accion', ['INSERT', 'UPDATE', 'DELETE']);
            $table->integer('id_registro');
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->timestamp('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_general');
    }
};
