<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAsignatura extends Model
{
    use HasFactory;

    protected $table = 'solicitud_asignaturas';

    protected $primaryKey = 'id_solicitud_asignatura';

    protected $fillable = [
        'solicitud_id',
        'asignaturas', // Ahora es un campo JSON
    ];

    protected $casts = [
        'asignaturas' => 'array', // Automáticamente convierte JSON a array y viceversa
    ];

    // Definir las relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id', 'id_solicitud');
    }
}
