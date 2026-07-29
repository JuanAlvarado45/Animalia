<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de {{ $mascota->nombre }} - Animalía</title>
    <!-- Cargar Tailwind (puedes usar tu propio app.css si lo prefieres) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Alerta de Extraviado -->
        @if($mascota->extraviado)
            <div class="bg-red-600 text-white text-center py-4 px-6 animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h2 class="text-2xl font-black uppercase tracking-wider">¡Estoy Perdido!</h2>
                <p class="text-sm font-medium mt-1">Por favor, contacta a mi familia.</p>
            </div>
        @else
            <div class="bg-emerald-500 text-white text-center py-2 px-6">
                <span class="text-sm font-bold uppercase tracking-widest">Mascota Protegida</span>
            </div>
        @endif

        <!-- Foto de la mascota -->
        <div class="h-64 bg-gray-200 relative">
            @if($mascota->foto)
                <img src="{{ Storage::url($mascota->foto) }}" class="w-full h-full object-cover" alt="Foto de {{ $mascota->nombre }}">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
        </div>

        <!-- Información -->
        <div class="p-6">
            <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-1">{{ $mascota->nombre }}</h1>
            <p class="text-center text-gray-500 font-medium mb-4">{{ ucfirst($mascota->especie) }} @if($mascota->raza) • {{ $mascota->raza }} @endif</p>

            <!-- Alertas médicas vitales: visibles siempre para cualquier persona que auxilie a la mascota -->
            @if($mascota->alergias || $mascota->condiciones_medicas)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4 text-sm">
                    <p class="font-bold text-amber-800 mb-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        Información médica importante
                    </p>
                    @if($mascota->alergias)
                        <p class="text-amber-900"><span class="font-semibold">Alergias:</span> {{ $mascota->alergias }}</p>
                    @endif
                    @if($mascota->condiciones_medicas)
                        <p class="text-amber-900"><span class="font-semibold">Condiciones:</span> {{ $mascota->condiciones_medicas }}</p>
                    @endif
                </div>
            @endif

<!-- Datos de contacto (Solo se muestran completos si está extraviado) -->
            <!-- Datos de contacto (Solo se muestran completos si está extraviado) -->
            @if($mascota->extraviado)
                <div class="bg-red-50 rounded-xl p-5 border border-red-100">
                    <h3 class="text-red-800 font-bold mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Datos del Dueño
                    </h3>
                    
                    <div class="space-y-2 text-sm text-gray-700">
                        <p><span class="font-semibold">Nombre:</span> {{ $mascota->user->name }}</p>
                        <!-- Asumiendo que agregaste 'telefono' y 'direccion' al modelo de usuario -->
                        @if($mascota->user->telefono)
                            <p><span class="font-semibold">Teléfono:</span> 
                                <a href="tel:{{ $mascota->user->telefono }}" class="text-blue-600 underline font-bold">{{ $mascota->user->telefono }}</a>
                            </p>
                        @endif
                        <p><span class="font-semibold">Email:</span> <a href="mailto:{{ $mascota->user->email }}" class="text-blue-600 underline">{{ $mascota->user->email }}</a></p>
                    </div>

                    @if($mascota->user->telefono)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $mascota->user->telefono) }}" target="_blank" class="mt-4 block w-full text-center bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition shadow-md">
                            Enviar WhatsApp
                        </a>
                    @endif
                </div>
            @else
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 text-center">
                    <p class="text-gray-600 text-sm">
                        Esta mascota se encuentra a salvo con su familia.
                    </p>
                    <p class="text-gray-400 text-xs mt-2">Dueño: {{ $mascota->user->name }}</p>
                </div>
            @endif
        </div>
        
        <div class="bg-gray-50 py-3 text-center text-xs text-gray-400 border-t border-gray-100">
            Generado por Animalía
        </div>
    </div>

</body>
</html>