<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomologacionAsignatura extends Model
{
    use HasFactory;

    protected $table = 'homologacion_asignaturas';

    protected $primaryKey = 'id_homologacion';

    protected $fillable = [
        'solicitud_id',
        'homologaciones', // Ahora es un campo JSON
        'fecha',
        'ruta_pdf_resolucion',
        'ruta_firma_imagen'
    ];

    protected $casts = [
        'homologaciones' => 'array', // Automáticamente convierte JSON a array y viceversa
        'fecha' => 'datetime',
    ];

    // Definir las relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id', 'id_solicitud');
    }
}
