<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    protected $primaryKey = 'id_asignatura';

    protected $fillable = [
        'pensum_id',
        'nombre',
        'tipo',
        'codigo_asignatura',
        'creditos',
        'semestre',
        'horas_sena',
        'tiempo_presencial',
        'tiempo_independiente',
        'horas_totales_semanales',
        'modalidad',
        'metodologia',
    ];

    // Relación con pensum
    public function pensum()
    {
        return $this->belongsTo(Pensum::class, 'pensum_id', 'id_pensum');
    }

    // Relación con contenidos programáticos
    public function contenidosProgramaticos()
    {
        return $this->hasMany(ContenidoProgramatico::class, 'asignatura_id', 'id_asignatura');
    }
}
