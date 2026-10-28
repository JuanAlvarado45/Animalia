<?php

use App\Http\Controllers\MascotaController;
use App\Http\Controllers\MedicinaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordatorioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VeterinariaController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\ConsultaController;

Route::get('/', function () {
    return view('welcome');
});

// =========================================================
// RUTA PÚBLICA: Perfil de la mascota al escanear el QR
// (Cualquier persona con un celular puede acceder)
// =========================================================
Route::get('/qr/{mascota}', [MascotaController::class, 'perfilQr'])->name('mascotas.qr');
// =========================================================
// DASHBOARD: redirige según el rol del usuario
// =========================================================
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = auth()->user();

    if ($user->rol === 'veterinario') {
        return redirect()->route('veterinario.panel');
    }

    $mascotas = $user->mascotas()->latest()->take(3)->get();

    return view('dashboard', compact('mascotas'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Perfil de usuario (todos los roles)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mapa de emergencias (todos los roles)
    Route::get('/mapa-emergencias', function () {
        return view('mapa.index');
    })->name('mapa.index');

    // -----------------------------------------------------
    // SOLO DUEÑOS
    // -----------------------------------------------------
    Route::middleware('rol:propietario')->group(function () {

        Route::resource('mascotas', MascotaController::class);

        // Guardar el QR (solo el dueño puede hacerlo)
        Route::post('/mascotas/{mascota}/guardar-qr', [MascotaController::class, 'guardarQr'])->name('mascotas.guardarQr');

        // Recordatorios (expediente / medicina preventiva)
        Route::post('/mascotas/{mascota}/recordatorios', [RecordatorioController::class, 'store'])->name('recordatorios.store');
        Route::put('/recordatorios/{recordatorio}', [RecordatorioController::class, 'update'])->name('recordatorios.update');
        Route::patch('/recordatorios/{recordatorio}/aplicar', [RecordatorioController::class, 'marcarAplicado'])->name('recordatorios.marcarAplicado');
        Route::delete('/recordatorios/{recordatorio}', [RecordatorioController::class, 'destroy'])->name('recordatorios.destroy');

        Route::get('/medicina-preventiva', [MedicinaController::class, 'index'])->name('medicina.index');

        Route::post('/mascotas/{mascota}/chat', [MascotaController::class, 'consultarIA'])->name('mascotas.chat');
    });

    // -----------------------------------------------------
    // SOLO VETERINARIOS
    // -----------------------------------------------------
    Route::middleware('rol:veterinario')->group(function () {

        Route::get('/veterinario/panel', function () {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $veterinaria = $user->veterinaria;

            return view('veterinario.panel', compact('veterinaria'));
        })->name('veterinario.panel');

        Route::get('/veterinario/clinica', [VeterinariaController::class, 'edit'])->name('veterinaria.edit');
        Route::put('/veterinario/clinica', [VeterinariaController::class, 'update'])->name('veterinaria.update');
        Route::get('/veterinario/buscar', [ExpedienteController::class, 'index'])->name('expediente.index');
        Route::post('/veterinario/buscar', [ExpedienteController::class, 'buscar'])->name('expediente.buscar');
        Route::get('/veterinario/expediente/{mascota:codigo_qr}', [ExpedienteController::class, 'show'])->name('expediente.show');
        Route::post('/veterinario/expediente/{mascota:codigo_qr}/consultas', [ConsultaController::class, 'store'])->name('consultas.store');
        Route::get('/veterinario/panel', [VeterinariaController::class, 'panel'])->name('veterinario.panel');
    });
});

require __DIR__.'/auth.php';