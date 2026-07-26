<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Banner de bienvenida -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary to-primary-dark p-8 text-white shadow-lg">
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-1">
                        {{ __('¡Bienvenido, :name!', ['name' => auth()->user()->name]) }}
                    </h3>
                    <p class="text-white/80">
                        {{ __('Este es tu panel de control. Aquí gestionarás el perfil de tus mascotas.') }}
                    </p>
                </div>
                <!-- Huella decorativa de fondo -->
                <svg class="absolute -right-6 -bottom-6 w-40 h-40 text-white/10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor">
                    <path d="M16 15c-4.8 0-8.5 3.3-8.5 7.4 0 3.5 3 6.1 6.4 6.1 1.1 0 1.7-.5 2.1-1 .4.5 1 1 2.1 1 3.4 0 6.4-2.6 6.4-6.1 0-4.1-3.7-7.4-8.5-7.4z"/>
                    <ellipse cx="7" cy="13" rx="2.9" ry="3.8" transform="rotate(-30 7 13)"/>
                    <ellipse cx="13" cy="6.5" rx="3" ry="4.2" transform="rotate(-10 13 6.5)"/>
                    <ellipse cx="19" cy="6.5" rx="3" ry="4.2" transform="rotate(10 19 6.5)"/>
                    <ellipse cx="25" cy="13" rx="2.9" ry="3.8" transform="rotate(30 25 13)"/>
                </svg>
            </div>

            <!-- Accesos rápidos -->
            <div class="grid sm:grid-cols-3 gap-6">

                <a href="{{ route('mascotas.index') }}" class="group bg-white rounded-xl shadow-md p-6 border-2 border-transparent
                                    hover:border-primary hover:shadow-xl hover:-translate-y-1
                                    transition-all duration-300">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-3
                                group-hover:bg-primary transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Registrar mascota</h4>
                    <p class="text-sm text-gray-500 mt-1">Agrega el perfil de tu mascota y genera su QR.</p>
                </a>

                <a  href="{{ route('medicina.index') }}" class="group bg-white rounded-xl shadow-md p-6 border-2 border-transparent
                                    hover:border-secondary hover:shadow-xl hover:-translate-y-1
                                    transition-all duration-300">
                    <div class="w-12 h-12 rounded-full bg-secondary/10 flex items-center justify-center mb-3
                                group-hover:bg-secondary transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-12A.75.75 0 014.5 6z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Medicina preventiva</h4>
                    <p class="text-sm text-gray-500 mt-1">Consulta las próximas vacunas y recordatorios.</p>
                </a>

                <a href="{{ route('mapa.index') }}" class="group bg-white rounded-xl shadow-md p-6 border-2 border-transparent
                                    hover:border-primary hover:shadow-xl hover:-translate-y-1
                                    transition-all duration-300">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-3
                                group-hover:bg-primary transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900">Mapa de emergencias</h4>
                    <p class="text-sm text-gray-500 mt-1">Localiza veterinarias de urgencia cerca de ti.</p>
                </a>

            </div>

            <!-- Lista de mascotas / Estado vacío -->
            @if ($mascotas->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-10 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Aún no tienes mascotas registradas</h4>
                    <p class="text-sm text-gray-500 mb-4">Registra a tu primera mascota para generar su código QR de identificación.</p>
                    <a href="{{ route('mascotas.create') }}" class="inline-block px-6 py-2.5 rounded-md bg-primary text-white font-semibold
                                        hover:bg-primary-dark transition-all duration-200 hover:scale-105">
                        + Registrar mi primera mascota
                    </a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-md p-6"
                     x-data="{
                         modalAbierto: false,
                         mascotaSeleccionada: null,
                         verMascota(mascota) {
                             this.mascotaSeleccionada = mascota;
                             this.modalAbierto = true;
                         }
                     }">

                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-gray-900">Tus mascotas</h4>
                        <a href="{{ route('mascotas.index') }}" class="text-sm text-primary hover:underline">Ver todas →</a>
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        @foreach ($mascotas as $mascota)
                            <button @click="verMascota({
                                        nombre: '{{ $mascota->nombre }}',
                                        especie: '{{ ucfirst($mascota->especie) }}',
                                        raza: '{{ $mascota->raza ?? 'No especificada' }}',
                                        sexo: '{{ $mascota->sexo ? ucfirst($mascota->sexo) : 'No especificado' }}',
                                        fechaNacimiento: '{{ $mascota->fecha_nacimiento ? $mascota->fecha_nacimiento->format('d/m/Y') : 'No registrada' }}',
                                        extraviado: {{ $mascota->extraviado ? 'true' : 'false' }},
                                        foto: '{{ $mascota->foto ? Storage::url($mascota->foto) : '' }}',
                                        url: '{{ route('mascotas.show', $mascota) }}'
                                    })"
                                    class="group flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-primary hover:shadow-md transition-all duration-300 text-left">

                                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center overflow-hidden shrink-0">
                                    @if ($mascota->foto)
                                        <img src="{{ Storage::url($mascota->foto) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.5 0 4.5-2.24 4.5-5S14.5 2 12 2 7.5 4.24 7.5 7s2 5 4.5 5zM5 22c0-4.5 3-8 7-8s7 3.5 7 8" />
                                        </svg>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate group-hover:text-primary transition-colors">{{ $mascota->nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($mascota->especie) }}</p>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Modal de consulta rápida -->
                    <div x-show="modalAbierto"
                         x-cloak
                         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
                         @click.self="modalAbierto = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">

                        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">

                            <template x-if="mascotaSeleccionada">
                                <div>
                                    <!-- Foto / encabezado -->
                                    <div class="h-40 bg-primary/10 flex items-center justify-center relative">
                                        <template x-if="mascotaSeleccionada.foto">
                                            <img :src="mascotaSeleccionada.foto" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!mascotaSeleccionada.foto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.5 0 4.5-2.24 4.5-5S14.5 2 12 2 7.5 4.24 7.5 7s2 5 4.5 5zM5 22c0-4.5 3-8 7-8s7 3.5 7 8" />
                                            </svg>
                                        </template>

                                        <button @click="modalAbierto = false" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center hover:bg-white transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-4">
                                            <h3 class="text-xl font-bold text-gray-900" x-text="mascotaSeleccionada.nombre"></h3>
                                            <span :class="mascotaSeleccionada.extraviado ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-700'"
                                                  class="text-xs font-medium px-2 py-1 rounded-full"
                                                  x-text="mascotaSeleccionada.extraviado ? 'Extraviado' : 'Al día'">
                                            </span>
                                        </div>

                                        <dl class="grid grid-cols-2 gap-y-3 text-sm">
                                            <dt class="text-gray-500">Especie</dt>
                                            <dd class="text-gray-900 font-medium" x-text="mascotaSeleccionada.especie"></dd>

                                            <dt class="text-gray-500">Raza</dt>
                                            <dd class="text-gray-900 font-medium" x-text="mascotaSeleccionada.raza"></dd>

                                            <dt class="text-gray-500">Sexo</dt>
                                            <dd class="text-gray-900 font-medium" x-text="mascotaSeleccionada.sexo"></dd>

                                            <dt class="text-gray-500">Nacimiento</dt>
                                            <dd class="text-gray-900 font-medium" x-text="mascotaSeleccionada.fechaNacimiento"></dd>
                                        </dl>

                                        <a :href="mascotaSeleccionada.url"
                                           class="block text-center mt-6 py-2.5 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition-all duration-200">
                                            Ver expediente completo
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>
</x-app-layout>