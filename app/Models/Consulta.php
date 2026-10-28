<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'mascota_id',
        'veterinaria_id',
        'motivo',
        'diagnostico',
        'tratamiento',
        'fecha_consulta',
    ];

    protected function casts(): array
    {
        return [
            'fecha_consulta' => 'date',
        ];
    }

    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }

    public function veterinaria()
    {
        return $this->belongsTo(Veterinaria::class);
    }
}