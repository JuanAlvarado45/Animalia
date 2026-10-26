<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Recordatorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'mascota_id',
        'tipo',
        'titulo',
        'descripcion',
        'fecha_programada',
        'fecha_aplicacion',
        'estado',
        'notificado_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_programada' => 'date',
            'fecha_aplicacion' => 'date',
            'notificado_at' => 'datetime',
        ];
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function scopeVencidos(Builder $query): Builder
    {
        return $query->where('estado', '!=', 'aplicado')
            ->whereDate('fecha_programada', '<', now()->toDateString());
    }

    public function scopeProximos(Builder $query, int $dias = 7): Builder
    {
        return $query->where('estado', '!=', 'aplicado')
            ->whereDate('fecha_programada', '>=', now()->toDateString())
            ->whereDate('fecha_programada', '<=', now()->addDays($dias)->toDateString());
    }

    public function scopeAplicados(Builder $query): Builder
    {
        return $query->where('estado', 'aplicado');
    }

    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo) {
            'vacuna' => 'Vacuna',
            'desparasitacion' => 'Desparasitación',
            'chequeo' => 'Chequeo médico',
            'medicamento' => 'Medicamento',
            default => 'Otro',
        };
    }

    public function getEstadoActualAttribute(): string
    {
        if ($this->estado === 'aplicado') {
            return 'aplicado';
        }

        if ($this->fecha_programada->isPast() && ! $this->fecha_programada->isToday()) {
            return 'vencido';
        }

        return 'pendiente';
    }
}