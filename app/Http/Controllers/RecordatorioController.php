<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Models\Recordatorio;
use Illuminate\Http\Request;

class RecordatorioController extends Controller
{
    public function store(Request $request, Mascota $mascota)
    {
        $this->authorize('update', $mascota);

        $validated = $request->validate([
            'tipo' => ['required', 'in:vacuna,desparasitacion,chequeo,medicamento,otro'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'fecha_programada' => ['required', 'date'],
        ]);

        $validated['mascota_id'] = $mascota->id;
        $validated['estado'] = 'pendiente';

        Recordatorio::create($validated);

        return redirect()->route('mascotas.show', $mascota)
            ->with('success', 'Recordatorio agregado al expediente.');
    }

    public function update(Request $request, Recordatorio $recordatorio)
    {
        $this->authorize('update', $recordatorio);

        $validated = $request->validate([
            'tipo' => ['required', 'in:vacuna,desparasitacion,chequeo,medicamento,otro'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'fecha_programada' => ['required', 'date'],
        ]);

        $recordatorio->update($validated);

        return redirect()->route('mascotas.show', $recordatorio->mascota_id)
            ->with('success', 'Recordatorio actualizado.');
    }

    public function marcarAplicado(Recordatorio $recordatorio)
    {
        $this->authorize('update', $recordatorio);

        $recordatorio->update([
            'estado' => 'aplicado',
            'fecha_aplicacion' => now(),
        ]);

        return back()->with('success', '¡Recordatorio marcado como aplicado!');
    }

    public function destroy(Recordatorio $recordatorio)
    {
        $this->authorize('delete', $recordatorio);

        $mascotaId = $recordatorio->mascota_id;
        $recordatorio->delete();

        return redirect()->route('mascotas.show', $mascotaId)
            ->with('success', 'Recordatorio eliminado.');
    }
}