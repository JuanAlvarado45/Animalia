<?php

namespace App\Http\Controllers;

use App\Models\Veterinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VeterinariaController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $veterinaria = $user->veterinaria;

        return view('veterinario.clinica', compact('veterinaria'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nombre_clinica' => ['required', 'string', 'max:255'],
            'cedula_profesional' => ['nullable', 'string', 'max:50'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'horario' => ['nullable', 'string', 'max:1000'],
            'urgencias_24h' => ['nullable', 'boolean'],
        ]);

        $validated['urgencias_24h'] = $request->boolean('urgencias_24h');

        Veterinaria::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return redirect()->route('veterinario.panel')
            ->with('success', 'Datos de la clínica guardados correctamente.');
    }

    public function panel()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $veterinaria = $user->veterinaria;
        
        $consultas = collect();

        // Si el veterinario ya tiene clínica, buscamos sus consultas
        if ($veterinaria) {
            $consultas = \App\Models\Consulta::where('veterinaria_id', $veterinaria->id)
                            ->with('mascota')
                            ->latest('fecha_consulta')
                            ->take(5)
                            ->get();
        }

        // Ojo: Asegúrate de que el nombre de tu vista ('veterinario.panel' o 'veterinario.dashboard') coincida con tu archivo .blade.php
        return view('veterinario.panel', compact('veterinaria', 'consultas'));
    }
    
}