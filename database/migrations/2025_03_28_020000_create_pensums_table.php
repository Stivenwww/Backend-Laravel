<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pensums', function (Blueprint $table) {
            $table->smallIncrements('id_pensum');
            $table->unsignedSmallInteger('programa_id');
            $table->year('anio'); // Año del pensum
            $table->timestamps();

            $table->foreign('programa_id')->references('id_programa')->on('programas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pensums');
    }
};
