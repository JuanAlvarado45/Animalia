<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Animalía') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">

    <!-- Navbar -->
    <header class="w-full px-6 py-4 flex justify-between items-center absolute top-0 left-0 z-20">
        <span class="text-2xl font-bold text-white drop-shadow">Animalía</span>

        <nav class="flex gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-md bg-white text-primary-dark font-semibold hover:bg-gray-100">
                    Ir al Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-md border-2 border-white text-white font-semibold hover:bg-white hover:text-primary-dark transition-all duration-200 hover:scale-105">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-md bg-secondary text-white font-semibold hover:bg-secondary-dark transition-transform duration-200 hover:scale-105 shadow-md hover:shadow-lg">
                    Registrarse
                </a>
            @endauth
        </nav>
    </header>

    <!-- Hero con imagen de fondo -->
    <section class="relative h-[600px] flex items-center justify-center text-center overflow-hidden">
       <img src="{{ asset('img/dog.jpg') }}"
     alt="Mascota feliz"
     class="absolute inset-0 w-full h-full object-cover">
       <div class="absolute inset-0 bg-gradient-to-t from-primary-dark/90 via-primary-dark/60 to-primary-dark/30"></div>

        <div class="relative z-10 max-w-2xl mx-auto px-6">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow">
                Cuidado integral para tus mascotas
            </h1>
            <p class="text-lg text-gray-100 mb-8">
                Historial médico centralizado, identificación con QR y localización
                de veterinarias de urgencia — todo en un solo lugar.
            </p>
           
        </div>
    </section>
    

    <!-- Carrusel de imágenes -->
    <section class="bg-white py-16 px-6">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">
            Momentos que cuidamos juntos
        </h2>

        <div class="max-w-6xl mx-auto relative"
            x-data="{
                slide: 0,
                visible: 3,
                images: [
                    '{{ asset('img/car1.jpg') }}',
                    '{{ asset('img/car2.jpg') }}',
                    '{{ asset('img/car3.jpg') }}',
                    '{{ asset('img/car4.jpg') }}',
                    '{{ asset('img/car5.jpg') }}',
                    '{{ asset('img/car6.jpg') }}'
                ],
                get maxSlide() { return this.images.length - this.visible },
                next() { this.slide = this.slide >= this.maxSlide ? 0 : this.slide + 1 },
                prev() { this.slide = this.slide <= 0 ? this.maxSlide : this.slide - 1 }
            }"
            x-init="setInterval(() => next(), 4000)">

            <!-- Ventana visible -->
            <div class="overflow-hidden">
                <div class="flex gap-6 transition-transform duration-700 ease-in-out"
                    :style="`transform: translateX(calc(-${slide} * (100% / ${visible} + 1.5rem)))`">

                    <template x-for="(img, index) in images" :key="index">
                        <div class="shrink-0 w-full md:w-[calc((100%-3rem)/3)]">
                            <img :src="img"
                                class="w-full h-64 object-cover rounded-xl shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        </div>
                    </template>

                </div>
            </div>

            <!-- Flechas -->
            <button @click="prev()" class="absolute -left-5 top-1/2 -translate-y-1/2 bg-white hover:bg-primary hover:text-white text-primary-dark rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition">
                ‹
            </button>
            <button @click="next()" class="absolute -right-5 top-1/2 -translate-y-1/2 bg-white hover:bg-primary hover:text-white text-primary-dark rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition">
                ›
            </button>

        </div>
    </section>

   <!-- Tarjetas de módulos -->
    <section class="max-w-6xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-10">
            ¿Qué puedes hacer con Animalía?
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <!-- Tarjeta 1: QR -->
            <div class="group bg-white rounded-xl shadow-md p-6 text-center border-2 border-transparent
                        hover:border-primary hover:shadow-2xl hover:-translate-y-2
                        transition-all duration-300 ease-out cursor-pointer">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center
                            group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6
                            transition-all duration-300 ease-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h4.5v4.5h-4.5v-4.5zM15.75 4.5h4.5v4.5h-4.5v-4.5zM3.75 15h4.5v4.5h-4.5V15zM15 15h1.5v1.5H15V15zM18.75 15h1.5v1.5h-1.5V15zM15 18h1.5v1.5H15V18zM18.75 18h1.5v1.5h-1.5V18z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary transition-colors duration-300">Identificación QR</h3>
                <p class="text-sm text-gray-600">
                    Placas con código dinámico que conectan a cualquier persona con tu contacto en caso de extravío.
                </p>
            </div>

            <!-- Tarjeta 2: Medicina Preventiva -->
            <div class="group bg-white rounded-xl shadow-md p-6 text-center border-2 border-transparent
                        hover:border-secondary hover:shadow-2xl hover:-translate-y-2
                        transition-all duration-300 ease-out cursor-pointer">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-secondary/10 flex items-center justify-center
                            group-hover:bg-secondary group-hover:scale-110 group-hover:rotate-6
                            transition-all duration-300 ease-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-secondary group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path fill="none" stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M4.5 6h15a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-15a.75.75 0 01-.75-.75v-12A.75.75 0 014.5 6z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-secondary transition-colors duration-300">Medicina Preventiva</h3>
                <p class="text-sm text-gray-600">
                    Recordatorios automáticos de vacunas y desparasitación según edad, raza y especie.
                </p>
            </div>

            <!-- Tarjeta 3: Geolocalización -->
            <div class="group bg-white rounded-xl shadow-md p-6 text-center border-2 border-transparent
                        hover:border-primary hover:shadow-2xl hover:-translate-y-2
                        transition-all duration-300 ease-out cursor-pointer">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-primary/10 flex items-center justify-center
                            group-hover:bg-primary group-hover:scale-110 group-hover:rotate-6
                            transition-all duration-300 ease-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary transition-colors duration-300">Mapa de Emergencias</h3>
                <p class="text-sm text-gray-600">
                    Encuentra al instante clínicas veterinarias con atención de urgencias 24/7 cerca de ti.
                </p>
            </div>

        </div>
    </section>

    <footer class="text-center text-sm text-gray-400 py-6 border-t">
        Animalía — Proyecto PROPTINOVA
    </footer>

</body>
</html>