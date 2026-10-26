<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
        $this->authorize('view', $mascota);

        $mascota->load(['recordatorios' => function ($query) {
            $query->orderBy('fecha_programada', 'desc');
        }]);

        $vencidos = $mascota->recordatorios->filter(fn ($r) => $r->estado_actual === 'vencido');
        $proximos = $mascota->recordatorios->filter(fn ($r) => $r->estado_actual === 'pendiente');
        $aplicados = $mascota->recordatorios->filter(fn ($r) => $r->estado_actual === 'aplicado');

        return view('mascotas.show', compact('mascota', 'vencidos', 'proximos', 'aplicados'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mascota $mascota)
    {
        $this->authorize('update', $mascota);
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
            'peso' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'alergias' => ['nullable', 'string', 'max:2000'],
            'condiciones_medicas' => ['nullable', 'string', 'max:2000'],
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

        $image_parts = explode(";base64,", $request->qr_image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $fileName = 'qr_mascotas/' . $mascota->id . '_' . uniqid() . '.png';

        Storage::disk('public')->put($fileName, $image_base64);

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
        $mascota->load('user');
        return view('mascotas.qr-perfil', compact('mascota'));
    }

    public function consultarIA(Request $request, Mascota $mascota)
    {
        $mensajeUsuario = $request->input('mensaje');

        $contexto = "El usuario pregunta sobre su mascota llamada {$mascota->nombre}. " .
                    "Es un {$mascota->especie} de raza {$mascota->raza}. " .
                    "Sexo: {$mascota->sexo}, Edad aproximada: {$mascota->edad}, Peso: {$mascota->peso} kg. ";

        $reglas = "Eres el 'Asistente Animalía', un consultor amigable. Reglas ESTRICTAS:\n" .
                  "1. Responde ÚNICAMENTE a lo que el usuario te pregunte en su último mensaje. Si el usuario solo dice 'Hola' o saluda, devuélvele el saludo en una sola línea y pregúntale cómo puedes ayudarle.\n" .
                  "2. Ve directo al grano. Sé extremadamente conciso.\n" .
                  "3. NO eres veterinario. No diagnostiques.\n" .
                  "4. FORMATO VISUAL OBLIGATORIO: Usa listas con viñetas (-), **negritas** para resaltar, y párrafos muy cortos.\n\n";

        $promptCompleto = $reglas . "Contexto de la mascota:\n" . $contexto . "\n\nPregunta del usuario:\n" . $mensajeUsuario;

        $apiKey = trim(env('GEMINI_API_KEY'));
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$apiKey}";
        
        try {
            $respuesta = Http::post($url, [
        'contents' => [
            [
                'parts' => [
                    ['text' => $promptCompleto]
                ]
            ]
        ],
        // Agrega este bloque de configuración para permitir respuestas más extensas
        'generationConfig' => [
            'maxOutputTokens' => 1000,
            'temperature' => 0.7
        ]
    ]);

            if ($respuesta->successful()) {
                $datos = $respuesta->json();
                $textoIA = $datos['candidates'][0]['content']['parts'][0]['text'] ?? 'Lo siento, hubo un problema al generar la respuesta.';
                return response()->json(['respuesta' => $textoIA]);
            }

            // Si Google rechaza la petición, mostramos el error exacto en el chat para diagnosticar
            $errorDetalle = $respuesta->json('error.message') ?? 'Clave de API inválida o error desconocido.';
            return response()->json([
                'respuesta' => '⚠️ Error de la API de Google: ' . $errorDetalle
            ], 500);

        } catch (\Exception $e) {
            return response()->json(['respuesta' => 'Excepción en el servidor: ' . $e->getMessage()], 500);
        }
    }
}