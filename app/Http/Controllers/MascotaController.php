<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MascotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mascotas = Auth::user()->mascotas()->latest()->get();

        return view('mascotas.index', compact('mascotas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        return view('mascotas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'especie' => ['required', 'in:perro,gato,otro'],
            'raza' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'sexo' => ['nullable', 'in:macho,hembra'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('mascotas', 'public');
        }

        $validated['user_id'] = Auth::id();

        Mascota::create($validated);

        return redirect()->route('mascotas.index')
            ->with('success', '¡Mascota registrada correctamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mascota $mascota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mascota $mascota)
    {
        $this->authorize('update', $mascota); // en edit() y update()
        return view('mascotas.edit', compact('mascota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mascota $mascota)
    {
        
        $this->authorize('update', $mascota); 
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'especie' => ['required', 'in:perro,gato,otro'],
            'raza' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'sexo' => ['nullable', 'in:macho,hembra'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'extraviado' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('foto')) {
            if ($mascota->foto) {
                Storage::disk('public')->delete($mascota->foto);
            }
            $validated['foto'] = $request->file('foto')->store('mascotas', 'public');
        }

        $validated['extraviado'] = $request->boolean('extraviado');

        $mascota->update($validated);

        return redirect()->route('mascotas.index')
            ->with('success', '¡Mascota actualizada correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mascota $mascota)
    {
        $this->authorize('delete', $mascota); 
        if ($mascota->foto) {
            Storage::disk('public')->delete($mascota->foto);
        }

        $mascota->delete();

        return redirect()->route('mascotas.index')
            ->with('success', 'Mascota eliminada correctamente.');
    }
    public function guardarQr(Request $request, Mascota $mascota)
    {
        $request->validate([
            'qr_image' => 'required|string'
        ]);

        // La imagen viene en Base64 desde el frontend
        $image_parts = explode(";base64,", $request->qr_image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        // Crear un nombre único para el archivo
        $fileName = 'qr_mascotas/' . $mascota->id . '_' . uniqid() . '.png';

        // Guardar en el storage (storage/app/public/qr_mascotas)
        Storage::disk('public')->put($fileName, $image_base64);

        // Guardar la ruta en la base de datos
        $mascota->update([
            'qr_path' => $fileName
        ]);

        return response()->json([
            'success' => true, 
            'qr_url' => Storage::url($fileName)
        ]);
    }

    public function perfilQr(Mascota $mascota)
    {
        // Cargar los datos del dueño (usuario) asociado a la mascota
        $mascota->load('user');
        
        return view('mascotas.qr-perfil', compact('mascota'));
    }

}
