<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultaController extends Controller
{
    public function store(Request $request, Mascota $mascota)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $veterinaria = $user->veterinaria;

        // Sin perfil de clínica no se puede registrar una consulta
        abort_unless($veterinaria, 403, 'Completa el perfil de tu clínica primero.');

        $validated = $request->validate([
            'motivo' => ['required', 'string', 'max:255'],
            'diagnostico' => ['nullable', 'string', 'max:2000'],
            'tratamiento' => ['nullable', 'string', 'max:2000'],
            'fecha_consulta' => ['required', 'date'],
        ]);

        $mascota->consultas()->create($validated + [
            'veterinaria_id' => $veterinaria->id,
        ]);

        return redirect()->route('expediente.show', $mascota)
            ->with('success', 'Consulta registrada correctamente.');
    }
}