<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;

Route::get('/', function () {
    return view('welcome');
});

// =========================================================
// RUTA PÚBLICA: Perfil de la mascota al escanear el QR
// (Cualquier persona con un celular puede acceder)
// =========================================================
Route::get('/qr/{mascota}', [MascotaController::class, 'perfilQr'])->name('mascotas.qr');


Route::get('/dashboard', function () {
    $mascotas = auth()->user()->mascotas()->latest()->take(3)->get();
    return view('dashboard', compact('mascotas'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de mascotas
    Route::resource('mascotas', MascotaController::class);
    
    // RUTA PRIVADA: Guardar el QR (Solo el dueño puede hacerlo)
    Route::post('/mascotas/{mascota}/guardar-qr', [MascotaController::class, 'guardarQr'])->name('mascotas.guardarQr');
    
    // Otras vistas
    Route::get('/medicina-preventiva', function () {return view('medicina.index');})->name('medicina.index');
    Route::get('/mapa-emergencias', function () {return view('mapa.index');})->name('mapa.index');
});

require __DIR__.'/auth.php';