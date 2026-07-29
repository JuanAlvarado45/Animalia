<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Expediente de :nombre', ['nombre' => $mascota->nombre]) }}
            </h2>
            <a href="{{ route('mascotas.index') }}" class="text-sm text-primary hover:underline">← Volver a mis mascotas</a>
        </div>
    </x-slot>

    <div x-data="expedienteComponent()" class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary-dark rounded-xl p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
                    <p class="font-semibold mb-1">Revisa los siguientes datos:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Encabezado del expediente -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="sm:flex">
                    <div class="sm:w-56 h-56 sm:h-auto bg-primary/10 flex items-center justify-center shrink-0">
                        @if ($mascota->foto)
                            <img src="{{ Storage::url($mascota->foto) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.5 0 4.5-2.24 4.5-5S14.5 2 12 2 7.5 4.24 7.5 7s2 5 4.5 5zM5 22c0-4.5 3-8 7-8s7 3.5 7 8" />
                            </svg>
                        @endif
                    </div>

                    <div class="p-6 flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $mascota->nombre }}</h3>
                                <p class="text-gray-500">{{ ucfirst($mascota->especie) }}@if($mascota->raza) · {{ $mascota->raza }} @endif</p>
                            </div>
                            @if ($mascota->extraviado)
                                <span class="text-xs font-semibold bg-red-100 text-red-600 px-3 py-1.5 rounded-full">Reportada como extraviada</span>
                            @else
                                <span class="text-xs font-semibold bg-green-100 text-green-700 px-3 py-1.5 rounded-full">Al resguardo de su familia</span>
                            @endif
                        </div>

                        <dl class="grid grid-cols-2 sm:grid-cols-4 gap-y-4 text-sm">
                            <div>
                                <dt class="text-gray-400">Sexo</dt>
                                <dd class="font-medium text-gray-900">{{ $mascota->sexo ? ucfirst($mascota->sexo) : '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Edad</dt>
                                <dd class="font-medium text-gray-900">{{ $mascota->edad ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Peso</dt>
                                <dd class="font-medium text-gray-900">{{ $mascota->peso ? $mascota->peso.' kg' : '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400">Nacimiento</dt>
                                <dd class="font-medium text-gray-900">{{ $mascota->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</dd>
                            </div>
                        </dl>

                        <div class="flex gap-2 mt-6">
                            <a href="{{ route('mascotas.edit', $mascota) }}" class="text-sm px-4 py-2 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                                Editar datos generales
                            </a>
                            <button type="button" @click="modalMedico = true" class="text-sm px-4 py-2 rounded-md bg-primary/10 text-primary font-medium hover:bg-primary hover:text-white transition">
                                Editar alertas médicas
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas médicas (visibles en el perfil público QR si la mascota se reporta extraviada) -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    Alertas médicas
                </h3>

                <div class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div class="bg-red-50/60 border border-red-100 rounded-lg p-4">
                        <p class="font-semibold text-red-700 mb-1">Alergias</p>
                        <p class="text-gray-700 whitespace-pre-line">{{ $mascota->alergias ?: 'Sin alergias registradas.' }}</p>
                    </div>
                    <div class="bg-amber-50/60 border border-amber-100 rounded-lg p-4">
                        <p class="font-semibold text-amber-700 mb-1">Condiciones médicas</p>
                        <p class="text-gray-700 whitespace-pre-line">{{ $mascota->condiciones_medicas ?: 'Sin condiciones registradas.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Resumen de recordatorios -->
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-400">
                    <p class="text-sm text-gray-500">Vencidos</p>
                    <p class="text-3xl font-bold text-red-500">{{ $vencidos->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-secondary">
                    <p class="text-sm text-gray-500">Próximos</p>
                    <p class="text-3xl font-bold text-secondary">{{ $proximos->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-primary">
                    <p class="text-sm text-gray-500">Aplicados</p>
                    <p class="text-3xl font-bold text-primary">{{ $aplicados->count() }}</p>
                </div>
            </div>

            <!-- Historial de medicina preventiva -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-gray-900">Vacunas, desparasitaciones y chequeos</h3>
                    <button type="button" @click="modalRecordatorio = true" class="text-sm px-4 py-2 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition">
                        + Agregar recordatorio
                    </button>
                </div>

                @if ($mascota->recordatorios->isEmpty())
                    <p class="text-sm text-gray-500 py-8 text-center">Aún no hay recordatorios registrados para {{ $mascota->nombre }}.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($mascota->recordatorios->sortBy('fecha_programada') as $r)
                            <div class="flex items-start gap-4 {{ $r->estado_actual === 'aplicado' ? 'opacity-60' : '' }}">
                                <div @class([
                                        'w-10 h-10 rounded-full flex items-center justify-center shrink-0',
                                        'bg-red-100' => $r->estado_actual === 'vencido',
                                        'bg-secondary/10' => $r->estado_actual === 'pendiente',
                                        'bg-primary/10' => $r->estado_actual === 'aplicado',
                                    ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" @class([
                                            'h-5 w-5',
                                            'text-red-500' => $r->estado_actual === 'vencido',
                                            'text-secondary' => $r->estado_actual === 'pendiente',
                                            'text-primary' => $r->estado_actual === 'aplicado',
                                        ]) fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        @if ($r->estado_actual === 'aplicado')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-12A.75.75 0 014.5 6z" />
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-wrap justify-between items-center gap-2">
                                        <p class="font-medium text-gray-900">{{ $r->tipo_label }} — {{ $r->titulo }}</p>
                                        @if ($r->estado_actual === 'vencido')
                                            <span class="text-xs font-medium bg-red-100 text-red-600 px-2 py-1 rounded-full">Vencido</span>
                                        @elseif ($r->estado_actual === 'aplicado')
                                            <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full">Aplicado</span>
                                        @else
                                            <span class="text-xs font-medium bg-secondary/10 text-secondary px-2 py-1 rounded-full">Pendiente</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        @if ($r->estado_actual === 'aplicado')
                                            Aplicado el {{ $r->fecha_aplicacion?->format('d/m/Y') }}
                                        @else
                                            Programado para el {{ $r->fecha_programada->format('d/m/Y') }}
                                        @endif
                                    </p>
                                    @if ($r->descripcion)
                                        <p class="text-sm text-gray-500 mt-1">{{ $r->descripcion }}</p>
                                    @endif
                                </div>

                                <div class="flex gap-2 shrink-0">
                                    @if ($r->estado_actual !== 'aplicado')
                                        <form action="{{ route('recordatorios.marcarAplicado', $r) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs px-3 py-1.5 rounded-md border border-primary text-primary font-medium hover:bg-primary hover:text-white transition">
                                                Marcar aplicado
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('recordatorios.destroy', $r) }}" method="POST" onsubmit="return confirm('¿Eliminar este recordatorio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs px-3 py-1.5 rounded-md border border-red-300 text-red-500 font-medium hover:bg-red-500 hover:text-white transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- MODAL: Agregar recordatorio -->
        <template x-teleport="body">
            <div x-show="modalRecordatorio" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto">
                <div x-show="modalRecordatorio" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60" @click="modalRecordatorio = false"></div>

                <div class="flex items-center justify-center min-h-screen p-4 relative">
                    <div x-show="modalRecordatorio"
                        x-transition:enter="ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-4">Nuevo recordatorio para {{ $mascota->nombre }}</h3>

                        <form method="POST" action="{{ route('recordatorios.store', $mascota) }}" class="space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="tipo" value="Tipo" />
                                <select id="tipo" name="tipo" required class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                    <option value="vacuna">Vacuna</option>
                                    <option value="desparasitacion">Desparasitación</option>
                                    <option value="chequeo">Chequeo médico</option>
                                    <option value="medicamento">Medicamento</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="titulo" value="Título" />
                                <x-text-input id="titulo" name="titulo" type="text" class="block mt-1 w-full" placeholder="Ej. Vacuna antirrábica" required />
                            </div>

                            <div>
                                <x-input-label for="fecha_programada" value="Fecha programada" />
                                <x-text-input id="fecha_programada" name="fecha_programada" type="date" class="block mt-1 w-full" required />
                            </div>

                            <div>
                                <x-input-label for="descripcion" value="Notas (opcional)" />
                                <textarea id="descripcion" name="descripcion" rows="2" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"></textarea>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="modalRecordatorio = false" class="flex-1 py-2.5 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                                    Cancelar
                                </button>
                                <button type="submit" class="flex-1 py-2.5 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- MODAL: Editar alertas médicas -->
        <template x-teleport="body">
            <div x-show="modalMedico" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto">
                <div x-show="modalMedico" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60" @click="modalMedico = false"></div>

                <div class="flex items-center justify-center min-h-screen p-4 relative">
                    <div x-show="modalMedico"
                        x-transition:enter="ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-4">Alertas médicas de {{ $mascota->nombre }}</h3>

                        <form method="POST" action="{{ route('mascotas.update', $mascota) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <!-- Se reenvían los campos requeridos por la validación de update() -->
                            <input type="hidden" name="nombre" value="{{ $mascota->nombre }}">
                            <input type="hidden" name="especie" value="{{ $mascota->especie }}">
                            <input type="hidden" name="raza" value="{{ $mascota->raza }}">
                            <input type="hidden" name="fecha_nacimiento" value="{{ $mascota->fecha_nacimiento?->format('Y-m-d') }}">
                            <input type="hidden" name="sexo" value="{{ $mascota->sexo }}">
                            <input type="hidden" name="extraviado" value="{{ $mascota->extraviado ? 1 : 0 }}">

                            <div>
                                <x-input-label for="peso" value="Peso (kg)" />
                                <x-text-input id="peso" name="peso" type="number" step="0.01" min="0" class="block mt-1 w-full" :value="$mascota->peso" />
                            </div>

                            <div>
                                <x-input-label for="alergias" value="Alergias" />
                                <textarea id="alergias" name="alergias" rows="2" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">{{ $mascota->alergias }}</textarea>
                            </div>

                            <div>
                                <x-input-label for="condiciones_medicas" value="Condiciones médicas" />
                                <textarea id="condiciones_medicas" name="condiciones_medicas" rows="2" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">{{ $mascota->condiciones_medicas }}</textarea>
                            </div>

                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="modalMedico = false" class="flex-1 py-2.5 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                                    Cancelar
                                </button>
                                <button type="submit" class="flex-1 py-2.5 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function expedienteComponent() {
            return {
                modalRecordatorio: false,
                modalMedico: false,
            }
        }
    </script>
</x-app-layout>
