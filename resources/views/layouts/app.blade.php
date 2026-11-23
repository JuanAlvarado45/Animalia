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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- =========================================================
            MICRO-ANIMACIONES GLOBALES ANIMALÍA (VISTAS + MODALES)
        ========================================================= -->
        <style>
            /* 1. Entrada suave del contenido principal al cambiar de página */
            main {
                animation: animaliaFadeIn 0.35s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            }

            @keyframes animaliaFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(8px);
                }
                to {
                    opacity: 1;
                    transform: none;
                }
            }

            /* 2. Botones y enlaces tipo botón (en vistas, modales y SweetAlert) */
            main button:not(:disabled):not([style*="max-height"] button),
            .fixed button:not(:disabled),
            .swal2-popup button:not(:disabled),
            main a[class*="rounded-md"],
            main a[class*="rounded-lg"],
            main a[class*="rounded-xl"],
            .fixed a[class*="rounded-md"],
            .fixed a[class*="rounded-lg"],
            .fixed a[class*="rounded-xl"] {
                transition:
                    transform 0.2s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.2s ease,
                    background-color 0.2s ease,
                    border-color 0.2s ease,
                    color 0.2s ease,
                    opacity 0.2s ease !important;
            }

            main button:not(:disabled):not([style*="max-height"] button):hover,
            .fixed button:not(:disabled):hover,
            .swal2-popup button:not(:disabled):hover,
            main a[class*="rounded-md"]:hover,
            main a[class*="rounded-lg"]:hover,
            main a[class*="rounded-xl"]:hover,
            .fixed a[class*="rounded-md"]:hover,
            .fixed a[class*="rounded-lg"]:hover,
            .fixed a[class*="rounded-xl"]:hover {
                transform: translateY(-2px);
            }

            main button:not(:disabled):not([style*="max-height"] button):active,
            .fixed button:not(:disabled):active,
            .swal2-popup button:not(:disabled):active,
            main a[class*="rounded-md"]:active,
            main a[class*="rounded-lg"]:active,
            main a[class*="rounded-xl"]:active,
            .fixed a[class*="rounded-md"]:active,
            .fixed a[class*="rounded-lg"]:active,
            .fixed a[class*="rounded-xl"]:active {
                transform: translateY(0) scale(0.97);
            }

            /* Resplandor suave en botones principales (bg-primary) */
            main button[class*="bg-primary"]:hover,
            main a[class*="bg-primary"]:hover,
            .fixed button[class*="bg-primary"]:hover {
                box-shadow: 0 8px 20px -4px rgba(13, 148, 136, 0.38);
            }

            /* Resplandor suave en botones de confirmación esmeralda dentro de modales */
            .fixed button[class*="border-emerald"]:hover {
                box-shadow: 0 6px 16px -4px rgba(16, 185, 129, 0.28);
            }

            /* 3. Modales (Ver QR, Eliminar, etc.) con desenfoque y curva elástica */
            .fixed.inset-0 > .fixed.inset-0[class*="bg-gray-900"],
            .fixed.inset-0[class*="bg-gray-500"] {
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
                transition: opacity 0.25s ease, backdrop-filter 0.25s ease !important;
            }

            .fixed.inset-0 .bg-white[class*="rounded-"] {
                transition:
                    transform 0.32s cubic-bezier(0.22, 1, 0.36, 1),
                    opacity 0.25s ease,
                    box-shadow 0.3s ease !important;
            }

            /* Aparición suave de la imagen QR dentro del modal */
            .fixed.inset-0 img {
                animation: animaliaZoomIn 0.35s cubic-bezier(0.22, 1, 0.36, 1);
            }

            @keyframes animaliaZoomIn {
                from {
                    opacity: 0;
                    transform: scale(0.92);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            /* SweetAlert2 alineado al mismo estilo */
            .swal2-container {
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
            }

            .swal2-popup {
                border-radius: 1rem !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            }

            /* 4. Tarjetas y bloques de interés */
            main .bg-white.rounded-xl,
            main .bg-white.rounded-2xl {
                transition:
                    transform 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.28s ease,
                    border-color 0.25s ease;
            }

            main .grid > .bg-white.rounded-xl:hover,
            main .grid > a.rounded-xl:hover {
                transform: translateY(-4px);
                box-shadow: 0 14px 28px -6px rgba(13, 148, 136, 0.12), 0 6px 12px -4px rgba(0, 0, 0, 0.05);
            }

            main .grid > div:hover img {
                transform: scale(1.04);
            }
            main .grid > div img {
                transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
            }

            /* 5. Inputs y selects más fluidos al enfocar */
            main input:not([type="file"]):not([type="checkbox"]),
            main select,
            main textarea {
                transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
            }

            /* 6. Alertas y mensajes de éxito */
            main [class*="bg-primary/10"][class*="border"],
            main [class*="bg-red-50"][class*="border"] {
                animation: animaliaSlideAlert 0.4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            }

            @keyframes animaliaSlideAlert {
                from {
                    opacity: 0;
                    transform: translateY(-10px) scale(0.98);
                }
                to {
                    opacity: 1;
                    transform: none;
                }
            }

            /* 7. Aparición escalonada al entrar en pantalla */
            .animalia-reveal {
                opacity: 0;
                transform: translateY(16px);
            }

            .animalia-reveal.is-visible {
                opacity: 1;
                transform: none;
                transition:
                    opacity 0.45s cubic-bezier(0.22, 1, 0.36, 1),
                    transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
            }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
        <!-- =========================================================
            MEJORAS VISUALES AVANZADAS (NAVBAR, BRILLOS, PULSOS Y LOADER)
        ========================================================= -->
        <style>
            /* 1. Barra superior de progreso al navegar o guardar */
            #animalia-progress-bar {
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                width: 0%;
                background: linear-gradient(90deg, #0d9488, #34d399, #059669);
                box-shadow: 0 0 10px rgba(16, 185, 129, 0.7);
                z-index: 99999;
                opacity: 0;
                pointer-events: none;
                transition: width 0.35s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.25s ease;
            }

            /* 2. Navbar fija con efecto cristal esmerilado */
            body > div > nav {
                position: sticky;
                top: 0;
                z-index: 40;
                background-color: rgba(255, 255, 255, 0.88) !important;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                transition: box-shadow 0.25s ease, background-color 0.25s ease;
            }

            body > div > nav.nav-scrolled {
                background-color: rgba(255, 255, 255, 0.96) !important;
                box-shadow: 0 4px 20px -4px rgba(17, 24, 39, 0.08);
            }

            /* Subrayado fluido en enlaces del menú superior */
            body > div > nav a[class*="inline-flex items-center"] {
                position: relative;
            }

            /* 3. Destello diagonal (Shine) en botones principales */
            main button[class*="bg-primary"],
            main a[class*="bg-primary"],
            .fixed button[class*="bg-primary"] {
                position: relative;
                overflow: hidden;
            }

            main button[class*="bg-primary"]::after,
            main a[class*="bg-primary"]::after,
            .fixed button[class*="bg-primary"]::after {
                content: "";
                position: absolute;
                top: 0;
                left: -120%;
                width: 60%;
                height: 100%;
                background: linear-gradient(
                    120deg,
                    transparent,
                    rgba(255, 255, 255, 0.28),
                    transparent
                );
                transform: skewX(-20deg);
                transition: left 0.6s cubic-bezier(0.22, 1, 0.36, 1);
                pointer-events: none;
            }

            main button[class*="bg-primary"]:hover::after,
            main a[class*="bg-primary"]:hover::after,
            .fixed button[class*="bg-primary"]:hover::after {
                left: 140%;
            }

            /* 4. Animación especial para la tarjeta "Registrar nueva mascota" */
            main a[class*="border-dashed"] > div:first-child {
                transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), background-color 0.25s ease, color 0.25s ease;
            }

            main a[class*="border-dashed"]:hover > div:first-child {
                transform: scale(1.12) rotate(90deg);
                background-color: rgba(13, 148, 136, 0.12);
            }

            /* 5. Pulso suave en etiquetas de alerta (Extraviado / Vencido) */
            main span.bg-red-100.text-red-600 {
                animation: animaliaPulseRed 2.4s infinite;
            }

            @keyframes animaliaPulseRed {
                0% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.35);
                }
                70% {
                    box-shadow: 0 0 0 6px rgba(220, 38, 38, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0);
                }
            }

            /* 6. Spinner automático al enviar formularios */
            button.animalia-loading {
                opacity: 0.85 !important;
                pointer-events: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            button.animalia-loading::before {
                content: "";
                width: 14px;
                height: 14px;
                border: 2px solid currentColor;
                border-top-color: transparent;
                border-radius: 9999px;
                animation: animaliaSpin 0.65s linear infinite;
                flex-shrink: 0;
            }

            @keyframes animaliaSpin {
                to { transform: rotate(360deg); }
            }
        </style>

        <div id="animalia-progress-bar"></div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const bar = document.getElementById('animalia-progress-bar');
                const nav = document.querySelector('body > div > nav');

                const iniciarBarra = () => {
                    if (!bar) return;
                    bar.style.opacity = '1';
                    bar.style.width = '35%';
                    setTimeout(() => { bar.style.width = '78%'; }, 180);
                };

                // Sombra dinámica en la navbar al bajar por la página
                if (nav) {
                    window.addEventListener('scroll', () => {
                        nav.classList.toggle('nav-scrolled', window.scrollY > 12);
                    }, { passive: true });
                }

                // Activa barra superior al navegar entre enlaces internos
                document.querySelectorAll('a[href]').forEach((link) => {
                    link.addEventListener('click', (e) => {
                        const href = link.getAttribute('href');
                        if (
                            !href ||
                            href.startsWith('#') ||
                            href.startsWith('javascript:') ||
                            link.hasAttribute('download') ||
                            link.target === '_blank' ||
                            e.ctrlKey || e.metaKey
                        ) return;
                        iniciarBarra();
                    });
                });

                // Activa barra superior + spinner en el botón al enviar cualquier formulario
                document.querySelectorAll('form').forEach((form) => {
                    form.addEventListener('submit', (e) => {
                        if (e.defaultPrevented) return;
                        iniciarBarra();

                        const btn = form.querySelector('button[type="submit"]') ||
                                    document.querySelector('.fixed button[style*="dc2626"]');
                        if (btn) {
                            btn.classList.add('animalia-loading');
                        }
                    });
                });

                // Limpia estados si el usuario vuelve con el botón "Atrás" del navegador
                window.addEventListener('pageshow', () => {
                    if (bar) {
                        bar.style.opacity = '0';
                        bar.style.width = '0%';
                    }
                    document.querySelectorAll('.animalia-loading').forEach((btn) => {
                        btn.classList.remove('animalia-loading');
                    });
                });
            });
        </script>
    </body>
</html>
