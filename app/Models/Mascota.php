<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Mascota extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
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
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     */
    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'extraviado' => 'boolean',
        ];
    }

    /**
     * Evento del ciclo de vida del modelo: se ejecuta automáticamente
     * cada vez que se va a crear una nueva mascota en la base de datos.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mascota) {
            $mascota->codigo_qr = (string) Str::uuid();
        });
    }

    /**
     * Relación: una mascota pertenece a un usuario (dueño).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}