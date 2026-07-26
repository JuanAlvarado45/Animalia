<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Medicina Preventiva') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Resumen de alertas -->
            <div class="grid sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-400">
                    <p class="text-sm text-gray-500">Vencidas</p>
                    <p class="text-3xl font-bold text-red-500">1</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-secondary">
                    <p class="text-sm text-gray-500">Próximas (7 días)</p>
                    <p class="text-3xl font-bold text-secondary">2</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-primary">
                    <p class="text-sm text-gray-500">Al día</p>
                    <p class="text-3xl font-bold text-primary">3</p>
                </div>
            </div>

            <!-- Línea de tiempo de eventos -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="font-semibold text-gray-900 mb-6">Próximos eventos</h3>

                <div class="space-y-5">

                    <!-- Evento vencido (EJEMPLO) -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p class="font-medium text-gray-900">Vacuna antirrábica — Firulais</p>
                                <span class="text-xs font-medium bg-red-100 text-red-600 px-2 py-1 rounded-full">Vencida</span>
                            </div>
                            <p class="text-sm text-gray-500">Venció el 15 de julio, 2026</p>
                        </div>
                    </div>

                    <!-- Evento próximo (EJEMPLO) -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-12A.75.75 0 014.5 6z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p class="font-medium text-gray-900">Desparasitación — Michi</p>
                                <span class="text-xs font-medium bg-secondary/10 text-secondary px-2 py-1 rounded-full">En 5 días</span>
                            </div>
                            <p class="text-sm text-gray-500">Programada para el 31 de julio, 2026</p>
                        </div>
                    </div>

                    <!-- Evento al día (EJEMPLO) -->
                    <div class="flex items-start gap-4 opacity-60">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p class="font-medium text-gray-900">Vacuna múltiple — Rocky</p>
                                <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full">Al día</span>
                            </div>
                            <p class="text-sm text-gray-500">Próxima dosis: 20 de octubre, 2026</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>