<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio;
use Illuminate\Support\Facades\Auth;

class MedicinaController extends Controller
{
    public function index()
    {
        $mascotaIds = Auth::user()->mascotas()->pluck('id');

        $recordatorios = Recordatorio::with('mascota')
            ->whereIn('mascota_id', $mascotaIds)
            ->orderBy('fecha_programada')
            ->get();

        $vencidos = $recordatorios->filter(fn ($r) => $r->estado_actual === 'vencido')
            ->sortBy('fecha_programada');

        $pendientes = $recordatorios->filter(fn ($r) => $r->estado_actual === 'pendiente');

        $limite = now()->addDays(7)->endOfDay();
        $proximos = $pendientes->filter(fn ($r) => $r->fecha_programada->lessThanOrEqualTo($limite))
            ->sortBy('fecha_programada');
        $alDia = $pendientes->filter(fn ($r) => $r->fecha_programada->greaterThan($limite))
            ->sortBy('fecha_programada');

        $aplicados = $recordatorios->filter(fn ($r) => $r->estado_actual === 'aplicado')
            ->sortByDesc('fecha_aplicacion');

        return view('medicina.index', compact('vencidos', 'proximos', 'alDia', 'aplicados'));
    }
}