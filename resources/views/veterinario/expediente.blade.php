<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Expediente de') }} {{ $mascota->nombre }}
            </h2>
            <a href="{{ route('expediente.index') }}" class="text-sm text-primary hover:underline">← Nueva búsqueda</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary-dark rounded-xl p-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($mascota->extraviado)
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
                    Esta mascota fue reportada como <strong>extraviada</strong>. Contacta al dueño de inmediato.
                </div>
            @endif

            <!-- Datos de la mascota -->
            <div class="bg-white rounded-xl shadow-md p-6 flex gap-6">
                <div class="w-28 h-28 rounded-xl bg-primary/10 overflow-hidden shrink-0 flex items-center justify-center">
                    @if ($mascota->foto)
                        <img src="{{ Storage::url($mascota->foto) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-primary/40 text-xs">Sin foto</span>
                    @endif
                </div>

                <dl class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm flex-1">
                    <dt class="text-gray-500">Nombre</dt>
                    <dd class="font-medium text-gray-900">{{ $mascota->nombre }}</dd>

                    <dt class="text-gray-500">Especie</dt>
                    <dd class="font-medium text-gray-900">{{ ucfirst($mascota->especie) }}</dd>

                    <dt class="text-gray-500">Raza</dt>
                    <dd class="font-medium text-gray-900">{{ $mascota->raza ?? 'No especificada' }}</dd>

                    <dt class="text-gray-500">Sexo</dt>
                    <dd class="font-medium text-gray-900">{{ $mascota->sexo ? ucfirst($mascota->sexo) : 'No especificado' }}</dd>

                    <dt class="text-gray-500">Nacimiento</dt>
                    <dd class="font-medium text-gray-900">{{ $mascota->fecha_nacimiento?->format('d/m/Y') ?? 'No registrada' }}</dd>

                    <dt class="text-gray-500">Peso</dt>
                    <dd class="font-medium text-gray-900">{{ $mascota->peso ? $mascota->peso.' kg' : 'No registrado' }}</dd>
                </dl>
            </div>

            <!-- Información médica -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Información médica</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Alergias</dt>
                        <dd class="text-gray-900">{{ $mascota->alergias ?: 'Ninguna registrada' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Condiciones médicas</dt>
                        <dd class="text-gray-900">{{ $mascota->condiciones_medicas ?: 'Ninguna registrada' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Contacto del dueño -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Dueño</h3>
                <dl class="grid grid-cols-2 gap-y-2 text-sm">
                    <dt class="text-gray-500">Nombre</dt>
                    <dd class="font-medium text-gray-900">{{ $mascota->user->name }}</dd>

                    <dt class="text-gray-500">Teléfono</dt>
                    <dd class="font-medium text-gray-900">{{ $mascota->user->telefono ?? 'No registrado' }}</dd>

                    <dt class="text-gray-500">Dirección</dt>
                    <dd class="font-medium text-gray-900">{{ $mascota->user->direccion ?? 'No registrada' }}</dd>
                </dl>
            </div>

            <!-- Historial de recordatorios -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Vacunas y tratamientos</h3>

                @forelse ($mascota->recordatorios->sortBy('fecha_programada') as $r)
                    <div class="flex justify-between items-start py-3 border-b border-gray-100 last:border-0">
                        <div>
                            <p class="font-medium text-gray-900">{{ $r->titulo }}</p>
                            <p class="text-sm text-gray-500">
                                {{ ucfirst($r->tipo) }} · Programado {{ \Carbon\Carbon::parse($r->fecha_programada)->format('d/m/Y') }}
                                @if ($r->fecha_aplicacion)
                                    · Aplicado {{ \Carbon\Carbon::parse($r->fecha_aplicacion)->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full
                            {{ $r->estado === 'aplicado' ? 'bg-primary/10 text-primary' : ($r->estado === 'vencido' ? 'bg-red-100 text-red-600' : 'bg-secondary/10 text-secondary') }}">
                            {{ ucfirst($r->estado) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Esta mascota aún no tiene registros.</p>
                @endforelse
            </div>

            <!-- Consultas -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Historial de consultas</h3>

                @if ($veterinaria)
                    <form method="POST" action="{{ route('consultas.store', $mascota) }}" class="space-y-4 mb-6 pb-6 border-b border-gray-100">
                        @csrf

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="fecha_consulta" :value="__('Fecha')" />
                                <x-text-input id="fecha_consulta" name="fecha_consulta" type="date" class="block mt-1 w-full"
                                            :value="old('fecha_consulta', now()->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('fecha_consulta')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="motivo" :value="__('Motivo de la consulta')" />
                                <x-text-input id="motivo" name="motivo" type="text" class="block mt-1 w-full"
                                            :value="old('motivo')" required />
                                <x-input-error :messages="$errors->get('motivo')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="diagnostico" :value="__('Diagnóstico (opcional)')" />
                            <textarea id="diagnostico" name="diagnostico" rows="2"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">{{ old('diagnostico') }}</textarea>
                            <x-input-error :messages="$errors->get('diagnostico')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tratamiento" :value="__('Tratamiento (opcional)')" />
                            <textarea id="tratamiento" name="tratamiento" rows="2"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">{{ old('tratamiento') }}</textarea>
                            <x-input-error :messages="$errors->get('tratamiento')" class="mt-2" />
                        </div>

                        <button type="submit" class="px-5 py-2.5 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition-all duration-200">
                            Registrar consulta
                        </button>
                    </form>
                @else
                    <div class="bg-secondary/10 border border-secondary/30 text-secondary-dark rounded-lg p-4 mb-6 text-sm">
                        Para registrar consultas primero
                        <a href="{{ route('veterinaria.edit') }}" class="font-semibold underline">completa el perfil de tu clínica</a>.
                    </div>
                @endif

                @forelse ($mascota->consultas as $c)
                    <div class="py-4 border-b border-gray-100 last:border-0">
                        <div class="flex justify-between items-start">
                            <p class="font-medium text-gray-900">{{ $c->motivo }}</p>
                            <span class="text-xs text-gray-500">{{ $c->fecha_consulta->format('d/m/Y') }}</span>
                        </div>
                        <p class="text-xs text-primary mb-2">{{ $c->veterinaria?->nombre_clinica ?? 'Clínica no disponible' }}</p>

                        @if ($c->diagnostico)
                            <p class="text-sm text-gray-600"><span class="text-gray-500">Diagnóstico:</span> {{ $c->diagnostico }}</p>
                        @endif
                        @if ($c->tratamiento)
                            <p class="text-sm text-gray-600"><span class="text-gray-500">Tratamiento:</span> {{ $c->tratamiento }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aún no hay consultas registradas.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>