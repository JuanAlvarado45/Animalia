<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class ExpedienteController extends Controller
{
    // Muestra el formulario de búsqueda
    public function index()
    {
        return view('veterinario.buscar');
    }

   public function buscar(Request $request)
    {
        $request->validate([
            'codigo' => ['required', 'string', 'max:255'],
        ]);

        $llave = 'buscar-expediente:'.Auth::id();

        if (RateLimiter::tooManyAttempts($llave, 10)) {
            $minutos = (int) ceil(RateLimiter::availableIn($llave) / 60);

            return back()->withInput()->withErrors([
                'codigo' => "Demasiados intentos fallidos. Intenta de nuevo en {$minutos} minuto(s).",
            ]);
        }

        $entrada = trim($request->codigo);
        $posibleUuid = Str::afterLast(rtrim($entrada, '/'), '/');

        if (Str::isUuid($posibleUuid)) {
            // UUID o enlace completo del QR
            $mascota = Mascota::where('codigo_qr', $posibleUuid)->first();
        } else {
            // Código corto: acepta minúsculas, espacios y guion
            $corto = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $entrada));
            $mascota = $corto !== ''
                ? Mascota::where('codigo_busqueda', $corto)->first()
                : null;
        }

        if (! $mascota) {
            RateLimiter::hit($llave, 600); // cuenta el fallo durante 10 minutos

            return back()->withInput()->withErrors([
                'codigo' => 'No encontramos una mascota con ese código.',
            ]);
        }

        RateLimiter::clear($llave);

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