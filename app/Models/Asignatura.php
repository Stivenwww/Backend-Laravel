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
        'programas_id',
        'nombre',
        'tipo',
        'codigo_asignatura',
        'creditos',
        'semestre',
        'horas',
        'tiempo_presencial',
        'tiempo_independiente',
        'horas_totales_semanales',
        'modalidad',
        'metodologia',
    ];

    // Definir las relaciones
    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programas_id', 'id_programa');
    }
}
