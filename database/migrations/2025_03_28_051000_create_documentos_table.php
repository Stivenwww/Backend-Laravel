<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {

            $table->smallIncrements('id_documento');
            $table->unsignedSmallInteger('solicitud_id');
            $table->unsignedSmallInteger('usuario_id');
            $table->enum('tipo', [
                'Certificado de Notas',
                'Carta de Solicitud',
                'Certificación de Finalización de Estudios',
                'Copia de la Visa',
                'Copia del Pasaporte',
                'Documento de Identidad',
                'Contenido Programático',
                'Apostilla'
            ]);
            $table->string('ruta', 255);
            $table->timestamp('fecha_subida')->useCurrent();

            $table->timestamps();


            //Foráneas
            $table->foreign('solicitud_id')->references('id_solicitud')->on('solicitudes');
            $table->foreign('usuario_id')->references('id_usuario')->on('users')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
