<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinaria extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre_clinica',
        'cedula_profesional',
        'telefono',
        'direccion',
        'latitud',
        'longitud',
        'horario',
        'urgencias_24h',
    ];

    protected function casts(): array
    {
        return [
            'urgencias_24h' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
}