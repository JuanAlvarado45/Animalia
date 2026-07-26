<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mapa de Emergencias') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <!-- Aviso informativo -->
            <div class="bg-secondary/10 border border-secondary/30 text-secondary-dark rounded-xl p-4 mb-6 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <p class="text-sm">Este mapa solo muestra clínicas veterinarias con atención de urgencias las 24 horas, filtrando estéticas y tiendas de mascotas.</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">

                <!-- Mapa (placeholder) -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="h-[450px] bg-gray-100 flex flex-col items-center justify-center text-gray-400 gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <p class="text-sm">El mapa interactivo se cargará aquí (integración con Google Maps API)</p>
                    </div>
                </div>

                <!-- Lista de clínicas -->
                <div class="space-y-4">

                    <!-- Clínica (EJEMPLO) -->
                    <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-primary hover:shadow-xl transition-all duration-300">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-semibold text-gray-900">Veterinaria San Jorge</h4>
                            <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full shrink-0">
                                24/7
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mb-3">Av. Central 123, Tecámac · 1.2 km</p>
                        <a href="#" class="text-sm font-medium text-primary hover:underline">Ver en el mapa →</a>
                    </div>

                    <!-- Clínica (EJEMPLO) -->
                    <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-primary hover:shadow-xl transition-all duration-300">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-semibold text-gray-900">Clínica Veterinaria del Valle</h4>
                            <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full shrink-0">
                                24/7
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mb-3">Blvd. Tecámac 456 · 2.8 km</p>
                        <a href="#" class="text-sm font-medium text-primary hover:underline">Ver en el mapa →</a>
                    </div>

                    <!-- Clínica (EJEMPLO) -->
                    <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-primary hover:shadow-xl transition-all duration-300">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-semibold text-gray-900">Hospital Animal Urgencias 24h</h4>
                            <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full shrink-0">
                                24/7
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mb-3">Calle Reforma 789 · 3.5 km</p>
                        <a href="#" class="text-sm font-medium text-primary hover:underline">Ver en el mapa →</a>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>