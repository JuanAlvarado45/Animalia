<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel del Veterinario') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <!-- CONTENEDOR PRINCIPAL (Todo debe ir aquí adentro para respetar el ancho) -->
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Banner -->
            <div class="rounded-2xl bg-gradient-to-r from-primary to-primary-dark p-8 text-white shadow-lg">
                <h3 class="text-2xl font-bold mb-1">
                    {{ __('¡Bienvenido, :name!', ['name' => auth()->user()->name]) }}
                </h3>
                <p class="text-white/80">
                    {{ $veterinaria ? $veterinaria->nombre_clinica : 'Completa el perfil de tu clínica para comenzar.' }}
                </p>
            </div>

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary-dark rounded-xl p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (! $veterinaria)
                <div class="bg-secondary/10 border border-secondary/30 text-secondary-dark rounded-xl p-5 flex items-center justify-between gap-4">
                    <p class="text-sm">Aún no has registrado los datos de tu clínica.</p>
                    <a href="{{ route('veterinaria.edit') }}" class="shrink-0 px-4 py-2 rounded-md bg-secondary text-white font-semibold hover:bg-secondary-dark transition">
                        Completar perfil
                    </a>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $veterinaria->nombre_clinica }}</h4>
                            <p class="text-sm text-gray-500">{{ $veterinaria->direccion }}</p>
                            <p class="text-sm text-gray-500">{{ $veterinaria->telefono }}</p>
                        </div>
                        @if ($veterinaria->urgencias_24h)
                            <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full">Urgencias 24/7</span>
                        @endif
                    </div>
                    <a href="{{ route('veterinaria.edit') }}" class="text-sm font-medium text-primary hover:underline">Editar datos de la clínica →</a>
                </div>
            @endif
            
            <!-- Accesos rápidos -->
            <div class="grid sm:grid-cols-2 gap-6">
                <a href="{{ route('expediente.index') }}" class="group bg-white rounded-xl shadow-md p-6 border-2 border-transparent hover:border-primary hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <h4 class="font-semibold text-gray-900">Buscar mascota</h4>
                    <p class="text-sm text-gray-500 mt-1">Consulta el expediente con el código QR.</p>
                </a>

                <a href="{{ route('expediente.index') }}" class="group bg-white rounded-xl shadow-md p-6 border-2 border-transparent hover:border-secondary hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <h4 class="font-semibold text-gray-900">Registrar consulta</h4>
                    <p class="text-sm text-gray-500 mt-1">Agrega consultas y vacunas aplicadas.</p>
                </a>
            </div>

            <!-- Últimas consultas registradas -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Últimas consultas registradas</h3>
                
                @if(isset($consultas) && $consultas->count() > 0)
                    <div class="space-y-0">
                        @foreach($consultas as $consulta)
                            <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $consulta->motivo }}</p>
                                    <p class="text-sm text-gray-500">
                                        Mascota: <span class="font-medium">{{ $consulta->mascota->nombre ?? 'Desconocida' }}</span> · 
                                        {{ \Carbon\Carbon::parse($consulta->fecha_consulta)->format('d/m/Y') }}
                                    </p>
                                </div>
                                @if($consulta->mascota)
                                    <a href="{{ route('expediente.show', $consulta->mascota) }}" class="text-sm font-medium text-primary hover:underline">
                                        Ver expediente
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 bg-gray-50 p-4 rounded-lg text-center border border-gray-100">
                        Aún no has registrado ninguna consulta.
                    </p>
                @endif
            </div>

        </div> <!-- CIERRE DEL CONTENEDOR PRINCIPAL max-w-6xl -->
    </div>
</x-app-layout>