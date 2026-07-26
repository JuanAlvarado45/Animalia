<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="min-h-screen w-full flex flex-col sm:justify-center items-center pt-6 sm:pt-0
            bg-gradient-to-br from-primary/10 via-gray-50 to-secondary/10 relative">

                <!-- Botón regresar al inicio -->
                <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-primary-dark font-medium
                                    hover:text-primary transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Volver al inicio
                </a>

                <div>
                    <a href="/">
                        <x-application-logo class="w-32 h-32 mx-auto" />
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-6 px-8 py-6 bg-white shadow-xl overflow-hidden sm:rounded-2xl
                            border-t-4 border-primary">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
