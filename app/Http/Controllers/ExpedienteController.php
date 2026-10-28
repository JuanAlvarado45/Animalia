<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ExpedienteController extends Controller
{
    // Muestra el formulario de búsqueda
    public function index()
    {
        return view('veterinario.buscar');
    }

    // Procesa la búsqueda
    public function buscar(Request $request)
    {
        $request->validate([
            'codigo' => ['required', 'string', 'max:255'],
        ]);

        $codigo = $request->input('codigo');

        // Si el usuario pegó un enlace completo, extraemos solo la parte final
        if (filter_var($codigo, FILTER_VALIDATE_URL)) {
            $segmentos = explode('/', rtrim($codigo, '/'));
            $codigo = end($segmentos);
        }

        // Buscamos la mascota. 
 
        $mascota = Mascota::where('id', $codigo)
                          ->orWhere('codigo_qr', $codigo)
                          ->first();

        if (! $mascota) {
            return back()
                ->withInput()
                ->withErrors(['codigo' => 'No encontramos una mascota con ese código o enlace.']);
        }

        return redirect()->route('expediente.show', $mascota);
    }

    // Muestra el expediente
    public function show(Mascota $mascota)
    {
        $mascota->load([
            'user',
            'recordatorios',
            'consultas' => fn ($q) => $q->latest('fecha_consulta')->with('veterinaria'),
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $veterinaria = $user->veterinaria;

        return view('veterinario.expediente', compact('mascota', 'veterinaria'));
    }
}