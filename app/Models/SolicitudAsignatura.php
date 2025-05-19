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
        'asignaturas',
    ];

    // Remove the automatic cast since we're handling raw JSON strings
    // protected $casts = [
    //     'asignaturas' => 'array',
    // ];

    // Override the setter to prevent double encoding
    public function setAsignaturasAttribute($value)
    {
        // If value is already a JSON string, store it directly
        if (is_string($value) && $this->isJson($value)) {
            $this->attributes['asignaturas'] = $value;
        } else {
            // Otherwise encode it
            $this->attributes['asignaturas'] = json_encode($value);
        }
    }

    // Helper to check if a string is valid JSON
    private function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    // Getter to ensure we always get an array back
    public function getAsignaturasAttribute($value)
    {
        return json_decode($value, true);
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id', 'id_solicitud');
    }
}
