<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mapa de Veterinarias') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid lg:grid-cols-3 gap-6">
                
                <!-- Mapa -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-md overflow-hidden">
                    <div id="mapa" class="w-full h-[600px] bg-gray-100"></div>
                </div>

                <!-- Panel derecho con filtro y lista -->
                <div class="flex flex-col h-[600px]">
                    
                    <!-- Filtro 24 Horas -->
                    <div class="mb-4 flex items-center justify-between bg-white p-4 rounded-xl shadow-md border border-gray-100 shrink-0">
                        <span class="text-sm font-bold text-gray-700">Mostrar solo urgencias 24h</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="toggle-24h" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- Lista de clínicas (con scroll si hay muchas) -->
                    <div class="space-y-4 overflow-y-auto pr-2 pb-2">
                        
                        <!-- Clínica 24h: Nautilus -->
                        <div class="clinic-card bg-white rounded-xl shadow-md p-5 border-l-4 border-primary hover:shadow-xl transition-all duration-300" data-24h="true">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-900">Urgencias Veterinarias Nautilus</h4>
                                <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full shrink-0">24/7</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Bvd. Ojo de Agua Mz 103 Lt. 59, Ojo de Agua, Tecámac</p>
                            <button class="focus-mapa text-sm font-medium text-primary hover:underline" data-lat="19.6781" data-lng="-99.0125" data-title="Urgencias Veterinarias Nautilus">Ver en el mapa →</button>
                        </div>

                        <!-- Clínica Normal: PetZoo -->
                        <div class="clinic-card bg-white rounded-xl shadow-md p-5 border-l-4 border-gray-400 hover:shadow-xl transition-all duration-300" data-24h="false">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-900">PetZoo Clínica Veterinaria</h4>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Av. Felipe Villanueva 18, Tecámac Centro</p>
                            <button class="focus-mapa text-sm font-medium text-primary hover:underline" data-lat="19.7130" data-lng="-98.9715" data-title="PetZoo Clínica Veterinaria">Ver en el mapa →</button>
                        </div>

                        <!-- Clínica 24h: Mundo Veterinario -->
                        <div class="clinic-card bg-white rounded-xl shadow-md p-5 border-l-4 border-primary hover:shadow-xl transition-all duration-300" data-24h="true">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-900">Mundo Veterinario</h4>
                                <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full shrink-0">24/7</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Carretera México-Pachuca Km 38.5, Tecámac</p>
                            <button class="focus-mapa text-sm font-medium text-primary hover:underline" data-lat="19.7120" data-lng="-98.9700" data-title="Mundo Veterinario">Ver en el mapa →</button>
                        </div>

                        <!-- Clínica Normal: Koalas -->
                        <div class="clinic-card bg-white rounded-xl shadow-md p-5 border-l-4 border-gray-400 hover:shadow-xl transition-all duration-300" data-24h="false">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-900">Clínica Veterinaria Koalas</h4>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Col. Héroes de Tecámac, Ojo de Agua</p>
                            <button class="focus-mapa text-sm font-medium text-primary hover:underline" data-lat="19.6425" data-lng="-99.0145" data-title="Clínica Veterinaria Koalas">Ver en el mapa →</button>
                        </div>

                        <!-- Clínica 24h: Animania Vet -->
                        <div class="clinic-card bg-white rounded-xl shadow-md p-5 border-l-4 border-primary hover:shadow-xl transition-all duration-300" data-24h="true">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-900">Hospital Veterinario del Dr. Mickey</h4>
                                <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full shrink-0">24/7</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Grand Plaza, Av. Insurgentes 102, El Calvario, 55020 Ecatepec de Morelos, Méx.</p>
                            <button class="focus-mapa text-sm font-medium text-primary hover:underline" data-lat="19.601910307083813" data-lng="-99.05556778829403" data-title="Hospital Veterinario del Dr. Mickey">Ver en el mapa →</button>
                        </div>

                        <!-- Clínica Normal: KattCan Médica Veterinaria -->
                        <div class="clinic-card bg-white rounded-xl shadow-md p-5 border-l-4 border-gray-400 hover:shadow-xl transition-all duration-300" data-24h="false">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-900">KattCan Médica Veterinaria</h4>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Calvario Manzana 003 4TA, Tecámac Centro, Tecamac, 55740 Tecámac de Felipe Villanueva, Méx.</p>
                            <button class="focus-mapa text-sm font-medium text-primary hover:underline" data-lat="19.709805008529635" data-lng="-98.9723727737166" data-title="KattCan Médica Veterinaria">Ver en el mapa →</button>
                        </div>

                        <!-- Clínica 24h: Clínica Veterinaria San Bernardo -->
                        <div class="clinic-card bg-white rounded-xl shadow-md p-5 border-l-4 border-primary hover:shadow-xl transition-all duration-300" data-24h="true">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-900">Clínica Veterinaria San Bernardo</h4>
                                <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full shrink-0">24/7</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">Rancho La Lupita Manzana 38 Lote 10, Sierra Hermosa, 55749 Tecámac de Felipe Villanueva, Méx.</p>
                            <button class="focus-mapa text-sm font-medium text-primary hover:underline" data-lat="19.702769658532844" data-lng="-99.01102775966592" data-title="Clínica Veterinaria San Bernardo">Ver en el mapa →</button>
                        </div>

                        <!-- Clínica 24h: Hospital Veterinario Tierarzt -->
                        <div class="clinic-card bg-white rounded-xl shadow-md p-5 border-l-4 border-primary hover:shadow-xl transition-all duration-300" data-24h="true">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-900">Hospital Veterinario Tierarzt</h4>
                                <span class="text-xs font-medium bg-primary/10 text-primary px-2 py-1 rounded-full shrink-0">24/7</span>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">C. Mangos 114, Ojo de Agua, 55770 Ojo de Agua, Méx.</p>
                            <button class="focus-mapa text-sm font-medium text-primary hover:underline" data-lat="19.677935462920203" data-lng="-99.03310586831361" data-title="Hospital Veterinario Tierarzt">Ver en el mapa →</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RECUERDA PONER TU CLAVE ABAJO EN TU_API_KEY_AQUI -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkPCV7T9YFDXLOFek6mPV1MMN5KWfo258&callback=initMap" async defer></script>
    
    <script>
        let map;

        function initMap() {
            const defaultLocation = { lat: 19.6950, lng: -98.9800 };
            
            map = new google.maps.Map(document.getElementById('mapa'), {
                zoom: 12,
                center: defaultLocation,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [
                    {
                        featureType: "poi", 
                        elementType: "labels", 
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });

            const infoWindow = new google.maps.InfoWindow();
            
            // Seleccionamos todas las tarjetas de clínicas
            const cards = document.querySelectorAll('.clinic-card');

            cards.forEach(card => {
                const button = card.querySelector('.focus-mapa');
                const lat = parseFloat(button.getAttribute('data-lat'));
                const lng = parseFloat(button.getAttribute('data-lng'));
                const title = button.getAttribute('data-title');
                const position = { lat: lat, lng: lng };
                
                // Creamos el marcador para esta tarjeta
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: title
                });

                // Guardamos el marcador dentro de la tarjeta para poder ocultarlo después
                card.marker = marker;

                marker.addListener('click', () => {
                    infoWindow.setContent(`<strong>${title}</strong>`);
                    infoWindow.open(map, marker);
                });

                button.addEventListener('click', () => {
                    map.panTo(position);
                    map.setZoom(16);
                    
                    infoWindow.setContent(`<strong>${title}</strong>`);
                    infoWindow.open(map, marker);
                });
            });

            // --- Lógica del Botón Switch 24 Horas ---
            const toggleBtn = document.getElementById('toggle-24h');
            
            toggleBtn.addEventListener('change', (e) => {
                const showOnly24h = e.target.checked;

                cards.forEach(card => {
                    const is24h = card.getAttribute('data-24h') === 'true';
                    
                    if (showOnly24h && !is24h) {
                        // Si el filtro está activo y la clínica NO es 24h: ocultar tarjeta y marcador
                        card.style.display = 'none';
                        card.marker.setMap(null); 
                    } else {
                        // De lo contrario: mostrar tarjeta y marcador
                        card.style.display = 'block';
                        card.marker.setMap(map); 
                    }
                });
            });
        }
    </script>
</x-app-layout>