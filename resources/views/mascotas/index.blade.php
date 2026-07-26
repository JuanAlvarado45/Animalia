<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Mascotas') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-primary/10 border border-primary/30 text-primary-dark rounded-xl p-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Grid container -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($mascotas as $mascota)
                    <div class="group bg-white rounded-xl shadow-md overflow-hidden border-2 border-transparent hover:border-primary hover:shadow-xl transition-all duration-300 flex flex-col">

                        <!-- Foto -->
                        <div class="h-40 bg-primary/10 flex items-center justify-center overflow-hidden">
                            @if ($mascota->foto)
                                <img src="{{ Storage::url($mascota->foto) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.5 0 4.5-2.24 4.5-5S14.5 2 12 2 7.5 4.24 7.5 7s2 5 4.5 5zM5 22c0-4.5 3-8 7-8s7 3.5 7 8" />
                                </svg>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <!-- Cabecera de la Tarjeta -->
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $mascota->nombre }}</h3>
                                    <p class="text-sm text-gray-500">{{ ucfirst($mascota->especie) }}@if($mascota->raza) · {{ $mascota->raza }} @endif</p>
                                </div>
                                @if ($mascota->extraviado)
                                    <span class="text-xs font-medium bg-red-100 text-red-600 px-2 py-1 rounded-full">
                                        Extraviado
                                    </span>
                                @else
                                    <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                        Al día
                                    </span>
                                @endif
                            </div>

                            <!-- Espaciador para empujar los botones al fondo -->
                            <div class="flex-grow"></div>

                            <!-- Acciones de Lectura -->
                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('mascotas.show', $mascota) }}" class="flex-1 text-center text-sm py-2 rounded-md border border-gray-400 text-gray-600 font-medium hover:bg-gray-600 hover:text-white hover:border-gray-600 transition-all duration-200">
                                    Ver expediente
                                </a>
                                <a href="{{ route('mascotas.show', $mascota) }}" class="flex-1 text-center text-sm py-2 rounded-md bg-primary/10 text-primary font-medium hover:bg-primary hover:text-white transition">
                                    Ver QR
                                </a>
                            </div>

                            <!-- Acciones de Modificación (Editar / Eliminar) -->
                            <div class="flex gap-2 mt-2 pt-2 border-t border-gray-100">
                                
                                <!-- Botón Editar -->
                                <a href="{{ route('mascotas.edit', $mascota) }}" class="flex-1 text-center text-sm py-2 rounded-md border border-yellow-400 text-yellow-600 font-medium hover:bg-yellow-500 hover:text-white hover:border-yellow-500 transition-all duration-200">
                                    Editar
                                </a>
                                
                                <!-- Lógica de Alpine.js para el Modal de Eliminar -->
                                <div x-data="{ mostrarModal: false }" class="flex-1 flex">
                                    
                                    <!-- Botón que abre el Modal -->
                                    <button @click="mostrarModal = true" type="button" class="w-full text-center text-sm py-2 rounded-md border border-red-400 text-red-500 font-medium hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200">
                                        Eliminar
                                    </button>

                                    <!-- TELETRANSPORTE DEL MODAL AL BODY -->
                                    <template x-teleport="body">
                                        <div x-show="mostrarModal" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto">
                                            <!-- Fondo oscuro difuminado -->
                                            <div x-show="mostrarModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="mostrarModal = false"></div>

                                            <!-- Contenedor para centrar el modal en toda la pantalla -->
                                            <div class="flex items-center justify-center min-h-screen p-4 z-50 relative">
                                                
                                                <!-- Tarjeta del Modal -->
                                                <div x-show="mostrarModal" 
                                                    x-transition:enter="ease-out duration-300" 
                                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                    x-transition:leave="ease-in duration-200" 
                                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                    class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6 text-center transform transition-all">
                                                    
                                                    <!-- Ícono de Advertencia -->
                                                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                                                        <svg class="h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                    </div>

                                                    <!-- Texto del Modal -->
                                                    <h3 class="text-xl font-bold text-gray-900 mb-2">¿Eliminar mascota?</h3>
                                                    <p class="text-sm text-gray-500 mb-6">
                                                        Estás a punto de eliminar a <span class="font-bold text-gray-800">{{ $mascota->nombre }}</span>. <br>
                                                        Todos sus datos y su expediente se borrarán de forma permanente. Esta acción no se puede deshacer.
                                                    </p>

                                                    <!-- Botones del Modal -->
                                                    <div class="flex gap-3 justify-center">
                                                        <button @click="mostrarModal = false" type="button" class="flex-1 py-2.5 rounded-md border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                                                            Cancelar
                                                        </button>
                                                        <!-- Formulario real de eliminación -->
                                                        <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-full py-2.5 rounded-md bg-red-600 text-white font-medium hover:bg-red-700 transition-colors">
                                                                Sí, eliminar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- FIN DEL TELETRANSPORTE -->
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Tarjeta "Agregar nueva" -->
                <a href="{{ route('mascotas.create') }}" class="flex flex-col items-center justify-center gap-3 bg-white rounded-xl shadow-md border-2 border-dashed border-gray-300 hover:border-primary text-gray-400 hover:text-primary transition-all duration-300 min-h-[280px]">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <span class="font-medium">Registrar nueva mascota</span>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>