<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Mascota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'especie',
        'raza',
        'fecha_nacimiento',
        'sexo',
        'foto',
        'extraviado',
        'qr_path',
        'peso',
        'alergias',
        'condiciones_medicas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'extraviado' => 'boolean',
            'peso' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mascota) {
            $mascota->codigo_qr = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }

    /**
     * Edad legible de la mascota calculada a partir de su fecha de nacimiento.
     */
    public function getEdadAttribute(): ?string
    {
        if (! $this->fecha_nacimiento) {
            return null;
        }

        // Aseguramos que sea una instancia limpia de Carbon sin horas/minutos interfiriendo
        $nacimiento = Carbon::parse($this->fecha_nacimiento)->startOfDay();
        $hoy = now()->startOfDay();

        // Calculamos la diferencia como un intervalo preciso
        $diff = $nacimiento->diffAsCarbonInterval($hoy);

        $años = (int) $diff->years;
        $meses = (int) $diff->months;
        $días = (int) $diff->dayz;

        $partes = [];

        if ($años > 0) {
            $partes[] = $años . ' ' . ($años === 1 ? 'año' : 'años');
        }

        if ($meses > 0) {
            $partes[] = $meses . ' ' . ($meses === 1 ? 'mes' : 'meses');
        }

        if ($días > 0) {
            $partes[] = $días . ' ' . ($días === 1 ? 'día' : 'días');
        }

        if (empty($partes)) {
            return '0 días';
        }

        if (count($partes) === 1) {
            return $partes[0];
        }

        $ultimo = array_pop($partes);
        return implode(', ', $partes) . ' y ' . $ultimo;
    }
}