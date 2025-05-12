<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HistorialHomologacion;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = "solicitudes";

    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'usuario_id',
        'programa_destino_id',
        'finalizo_estudios',
        'fecha_finalizacion_estudios',
        'fecha_ultimo_semestre_cursado',
        'estado',
        'numero_radicado',
        'fecha_solicitud'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id_usuario');
    }

    public function programaDestino()
    {
        return $this->belongsTo(Programa::class, 'programa_destino_id', 'id_programa');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($solicitud) {
            $year = now()->year;

            $ultimoRadicado = Solicitud::whereYear('created_at', $year)
                ->orderBy('id_solicitud', 'desc')
                ->lockForUpdate() // Previene condiciones de carrera
                ->first();

            $consecutivo = $ultimoRadicado ? (int) substr($ultimoRadicado->numero_radicado, -4) + 1 : 1;
            $solicitud->numero_radicado = "HOM-{$year}-" . str_pad($consecutivo, 4, '0', STR_PAD_LEFT);
        });

        static::created(function ($solicitud) {
            // Crear historial automáticamente cuando se crea la solicitud
            HistorialHomologacion::create([
                'usuario_id' => $solicitud->usuario_id,
                'solicitud_id' => $solicitud->id_solicitud,
                'estado' => $solicitud->estado,
                'observaciones' => 'Solicitud creada automáticamente',
                'fecha' => now(),
            ]);
        });

        static::updated(function ($solicitud) {
            if ($solicitud->isDirty('estado')) {
                HistorialHomologacion::create([
                    'usuario_id' => $solicitud->usuario_id,
                    'solicitud_id' => $solicitud->id_solicitud,
                    'estado' => $solicitud->estado,
                    'observaciones' => 'Cambio automático de estado',
                    'fecha' => now(),
                ]);
            }
        });
    }
}
