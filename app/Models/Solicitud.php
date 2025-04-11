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
        'fecha_solicitud',
        'ruta_pdf_resolucion'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id_usuario');
    }

    public function programaDestino()
    {
        return $this->belongsTo(Programa::class, 'programa_destino_id', 'id_programa');
    }

    protected static function boot()
    {
        parent::boot();

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
            // Solo crear un nuevo historial si cambió el estado o se subió un PDF
            if ($solicitud->isDirty('estado') || $solicitud->isDirty('ruta_pdf_resolucion')) {
                $observaciones = [];

                if ($solicitud->isDirty('estado')) {
                    $observaciones[] = 'Cambio automático de estado';
                }

                if ($solicitud->isDirty('ruta_pdf_resolucion')) {
                    $observaciones[] = 'Se cargó o actualizó la resolución';
                }

                HistorialHomologacion::create([
                    'usuario_id' => $solicitud->usuario_id,
                    'solicitud_id' => $solicitud->id_solicitud,
                    'estado' => $solicitud->estado,
                    'observaciones' => implode(' y ', $observaciones),
                    'fecha' => now(),
                    'ruta_pdf_resolucion' => $solicitud->ruta_pdf_resolucion,
                ]);
            }
        });
    }

}

