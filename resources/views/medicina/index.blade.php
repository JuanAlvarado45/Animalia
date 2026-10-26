<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Medicina Preventiva') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="bg-primary/10 border border-primary/30 text-primary-dark rounded-xl p-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Resumen de alertas -->
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-400">
                    <p class="text-sm text-gray-500">Vencidas</p>
                    <p class="text-3xl font-bold text-red-500">{{ $vencidos->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-secondary">
                    <p class="text-sm text-gray-500">Próximas (7 días)</p>
                    <p class="text-3xl font-bold text-secondary">{{ $proximos->count() }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-primary">
                    <p class="text-sm text-gray-500">Al día</p>
                    <p class="text-3xl font-bold text-primary">{{ $alDia->count() }}</p>
                </div>
            </div>

            <!-- Línea de tiempo de eventos -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-6">Próximos eventos</h3>

                @php
                    $pendientesOrdenados = $vencidos->concat($proximos)->concat($alDia);
                @endphp

                @if ($pendientesOrdenados->isEmpty())
                    <p class="text-sm text-gray-500 py-8 text-center">
                        No tienes recordatorios pendientes. Agrégalos desde el
                        <a href="{{ route('mascotas.index') }}" class="text-primary underline">expediente de cada mascota</a>.
                    </p>
                @else
                    <div class="space-y-5">
                        @foreach ($pendientesOrdenados as $r)
                            <div class="flex items-start gap-4">
                                <div @class([
                                        'w-10 h-10 rounded-full flex items-center justify-center shrink-0',
                                        'bg-red-100' => $r->estado_actual === 'vencido',
                                        'bg-secondary/10' => $proximos->contains($r),
                                        'bg-primary/10' => $alDia->contains($r),
                                    ])>
                                    <svg xmlns="http://www.w3.org/2000/svg" @class([
                                            'h-5 w-5',
                                            'text-red-500' => $r->estado_actual === 'vencido',
                                            'text-secondary' => $proximos->contains($r),
                                            'text-primary' => $alDia->contains($r),
                                        ]) fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        @if ($r->estado_actual === 'vencido')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        @elseif ($proximos->contains($r))
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-12A.75.75 0 014.5 6z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center flex-wrap gap-2">
                                        <p class="font-medium text-gray-900">{{ $r->titulo }} — {{ $r->mascota->nombre }}</p>
                                        @if ($r->estado_actual === 'vencido')
                                            <span class="text-xs font-medium bg-red-100 text-red-600 px-2 py-1 rounded-full">Vencida</span>
                                        @elseif ($proximos->contains($r))
                                            <span class="text-xs font-medium bg-secondary/10 text-secondary px-2 py-1 rounded-full">
                                                En {{ now()->diffInDays($r->fecha_programada, false) }} día(s)
                                            </span>
                                        @else
                                            <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full">Al día</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        {{ $r->tipo_label }} · Programada para el {{ $r->fecha_programada->translatedFormat('d \d\e F, Y') }}
                                    </p>
                                </div>
                                <a href="{{ route('mascotas.show', $r->mascota) }}" class="text-xs px-3 py-1.5 rounded-md border border-gray-300 text-gray-600 font-medium hover:bg-gray-50 transition shrink-0">
                                    Ver expediente
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Historial reciente de eventos aplicados -->
            @if ($aplicados->isNotEmpty())
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-semibold text-gray-900 mb-6">Historial reciente</h3>
                    <div class="space-y-3">
                        @foreach ($aplicados->take(5) as $r)
                            <div class="flex items-center justify-between text-sm border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $r->titulo }} — {{ $r->mascota->nombre }}</p>
                                    <p class="text-gray-500">{{ $r->tipo_label }} · Aplicado el {{ $r->fecha_aplicacion?->format('d/m/Y') }}</p>
                                </div>
                                <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full">Aplicado</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>