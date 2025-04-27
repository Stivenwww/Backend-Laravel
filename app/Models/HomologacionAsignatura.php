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
        'asignatura_origen_id',
        'asignatura_destino_id',
        'nota_destino',
        'fecha',
        'comentarios',
    ];

    // Definir las relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id', 'id_solicitud');
    }

    public function asignaturaOrigen()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_origen_id', 'id_asignatura');
    }

    public function asignaturaDestino()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_destino_id', 'id_asignatura');
    }
}
