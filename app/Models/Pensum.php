<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pensum extends Model
{
    use HasFactory;

    protected $table = 'pensums';
    protected $primaryKey = 'id_pensum';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'programa_id',
        'anio',
    ];

    // Relaciones
    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id');
    }

    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'pensum_id');
    }
}
