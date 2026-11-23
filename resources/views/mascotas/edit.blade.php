<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Mascota: ') }} {{ $mascota->nombre }}
        </h2>
    </x-slot>

    <div x-data="selectorRazas(@js(old('especie', $mascota->especie)), @js(old('raza', $mascota->raza ?? '')))" class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-md p-8">

                <!-- Formulario de Edición -->
                <form method="POST" action="{{ route('mascotas.update', $mascota->id) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div>
                        <x-input-label for="nombre" :value="__('Nombre de la mascota')" />
                        <x-text-input id="nombre" name="nombre" type="text" class="block mt-1 w-full" :value="old('nombre', $mascota->nombre)" required autofocus />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- Especie -->
                    <div>
                        <x-input-label for="especie" :value="__('Especie')" />
                        <select id="especie" name="especie" required
                                x-model="especie"
                                @change="cargarRazas()"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                            <option value="perro" {{ old('especie', $mascota->especie) == 'perro' ? 'selected' : '' }}>Perro</option>
                            <option value="gato" {{ old('especie', $mascota->especie) == 'gato' ? 'selected' : '' }}>Gato</option>
                            <option value="otro" {{ old('especie', $mascota->especie) == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <x-input-error :messages="$errors->get('especie')" class="mt-2" />
                    </div>

                    <!-- Raza con escritura libre + lista deslizante filtrable -->
                    <div style="position: relative;" @click.outside="abiertoRazas = false">
                        <div class="flex items-center justify-between">
                            <x-input-label for="raza" :value="__('Raza (opcional)')" />
                            <span x-show="cargandoRazas" class="text-xs text-primary font-medium animate-pulse">
                                Cargando catálogo...
                            </span>
                        </div>

                        <div style="position: relative;" class="mt-1">
                            <x-text-input id="raza" name="raza" type="text"
                                          x-ref="inputRaza"
                                          x-model="raza"
                                          @focus="if(razas.length > 0 && !modoManual) abiertoRazas = true"
                                          @input="if(!modoManual && razas.length > 0) abiertoRazas = true"
                                          @keydown.escape="abiertoRazas = false"
                                          ::placeholder="placeholderRaza"
                                          autocomplete="off"
                                          style="padding-right: 2.5rem;"
                                          class="block w-full" />

                            <!-- Flecha alineada dentro del input -->
                            <button type="button"
                                    x-show="razas.length > 0"
                                    @click="modoManual = false; abiertoRazas = !abiertoRazas"
                                    tabindex="-1"
                                    title="Ver lista de razas"
                                    style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding: 0 0.75rem;"
                                    class="text-gray-400 hover:text-primary transition">
                                <svg class="w-4 h-4 transition-transform duration-200" :class="abiertoRazas ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Menú desplegable compacto -->
                        <div x-show="abiertoRazas && razas.length > 0"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             style="display: none; position: absolute; z-index: 30; width: 100%;"
                             class="mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden">

                            <!-- Lista deslizante -->
                            <div style="max-height: 200px; overflow-y: auto;" class="py-1">
                                <template x-for="item in razasFiltradas" :key="item">
                                    <button type="button"
                                            @click="seleccionarRaza(item)"
                                            class="w-full text-left px-4 py-2 text-sm flex items-center justify-between transition-colors"
                                            :class="raza === item ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700 hover:bg-primary/5 hover:text-primary'">
                                        <span x-text="item"></span>
                                        <svg x-show="raza === item" class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </template>

                                <!-- Mensaje cuando lo escrito no está en la lista -->
                                <div x-show="razasFiltradas.length === 0 && raza.trim() !== ''" class="px-4 py-2.5 text-xs text-gray-500">
                                    Se guardará "<span class="font-semibold text-gray-800" x-text="raza"></span>" como raza personalizada.
                                </div>
                            </div>

                            <!-- Pie fijo para escribir manualmente -->
                            <div class="border-t border-gray-100 bg-gray-50 p-1.5">
                                <button type="button"
                                        @click="activarEscrituraManual()"
                                        class="w-full text-left px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary/10 rounded-lg transition flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    <span>¿No aparece? Escribir manualmente</span>
                                </button>
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('raza')" class="mt-2" />
                    </div>

                    <!-- Sexo -->
                    <div>
                        <x-input-label for="sexo" :value="__('Sexo')" />
                        <select id="sexo" name="sexo" required
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                            <option value="macho" {{ old('sexo', $mascota->sexo) == 'macho' ? 'selected' : '' }}>Macho</option>
                            <option value="hembra" {{ old('sexo', $mascota->sexo) == 'hembra' ? 'selected' : '' }}>Hembra</option>
                        </select>
                        <x-input-error :messages="$errors->get('sexo')" class="mt-2" />
                    </div>

                    <div x-data="recortadorFoto()">
                        <x-input-label for="foto" :value="__('Foto (opcional)')" />

                        @if(isset($mascota) && $mascota->foto)
                            <div x-show="!imagenFinal && !editandoFoto" class="my-3">
                                <p class="text-xs text-gray-500 mb-1">Foto actual:</p>
                                <img src="{{ asset('storage/' . $mascota->foto) }}" alt="Foto actual"
                                    class="w-24 h-24 object-cover rounded-xl shadow-sm border border-gray-200">
                            </div>
                        @endif

                        <input id="foto" name="foto" type="file" accept="image/*"
                            x-ref="inputFoto"
                            @change="abrirEditor($event)"
                            class="block mt-1 w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                    file:bg-primary/10 file:text-primary file:font-medium
                                    hover:file:bg-primary hover:file:text-white file:transition-all file:duration-200">

                        <!-- Vista previa de la foto ya recortada -->
                        <div x-show="imagenFinal && !editandoFoto" style="display: none;"
                            class="mt-3 p-3 bg-gray-50 border border-gray-200 rounded-xl flex items-center gap-4">
                            <img :src="imagenFinal" alt="Vista previa recortada"
                                class="w-20 h-20 object-cover rounded-xl shadow-sm border border-primary/30">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Foto lista para subir</p>
                                <p class="text-xs text-gray-500">Encuadre cuadrado optimizado para el perfil y QR.</p>
                                <div class="flex gap-3 mt-2">
                                    <button type="button" @click="reabrirEditor()"
                                            class="text-xs font-semibold text-primary hover:underline">
                                        Volver a ajustar
                                    </button>
                                    <button type="button" @click="limpiarFoto()"
                                            class="text-xs font-semibold text-red-500 hover:underline">
                                        Quitar foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Panel claro para acomodar la foto -->
                        <div x-show="editandoFoto" style="display: none;"
                            class="mt-3 p-4 bg-white rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-medium text-gray-600">
                                    Arrastra para centrar y usa los botones o la rueda para acercar
                                </span>
                                <div class="flex gap-1.5">
                                    <button type="button" @click="zoom(-0.1)" title="Alejar"
                                            class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 text-xs font-semibold rounded-md hover:bg-gray-200 transition">
                                        -
                                    </button>
                                    <button type="button" @click="zoom(0.1)" title="Acercar"
                                            class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 text-xs font-semibold rounded-md hover:bg-gray-200 transition">
                                        +
                                    </button>
                                    <button type="button" @click="rotar()" title="Girar 90°"
                                            class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 text-xs font-medium rounded-md hover:bg-gray-200 transition">
                                        ↻ Girar
                                    </button>
                                </div>
                            </div>

                            <!-- Contenedor del lienzo en gris claro -->
                            <div style="width: 100%; height: 320px; overflow: hidden;" class="bg-gray-100 border border-gray-200 rounded-lg">
                                <img x-ref="imagenCropper" alt="Recortar" style="display: block; max-width: 100%;">
                            </div>

                            <div class="flex justify-end gap-2 mt-3">
                                <button type="button" @click="cancelarEdicion()"
                                        class="px-3.5 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    Cancelar
                                </button>
                                <button type="button" @click="aplicarRecorte()"
                                        class="px-4 py-1.5 text-xs font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition">
                                    Aplicar encuadre
                                </button>
                            </div>
                        </div>

                    <!-- Checkbox Extraviado -->
                    <div class="flex items-center pt-2">
                        <input type="hidden" name="extraviado" value="0">
                        <input id="extraviado" type="checkbox" name="extraviado" value="1" {{ old('extraviado', $mascota->extraviado) ? 'checked' : '' }} 
                               class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring-primary">
                        <label for="extraviado" class="ml-2 text-sm text-gray-600">
                            ¿Mascota reportada como extraviada? (Activa alerta de emergencia)
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3 pt-4">
                        <a href="{{ route('mascotas.index') }}" class="flex-1 text-center py-2.5 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="flex-1 py-2.5 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition-all duration-200 hover:scale-105">
                            {{ __('Actualizar mascota') }}
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

    <script>
        function selectorRazas(especieInicial = '', razaInicial = '') {
            return {
                especie: especieInicial,
                raza: razaInicial,
                razas: [],
                cargandoRazas: false,
                abiertoRazas: false,
                modoManual: false,

                respaldoPerros: [
                    'Mestizo / Criollo', 'Chihuahua', 'Labrador Retriever', 'Golden Retriever',
                    'Pastor Alemán', 'Bulldog Francés', 'Bulldog Inglés', 'Poodle / Caniche',
                    'Pug', 'Beagle', 'Husky Siberiano', 'Rottweiler', 'Dachshund / Salchicha',
                    'Schnauzer', 'Shih Tzu', 'Yorkshire Terrier', 'Border Collie', 'Pitbull', 'Xoloitzcuintle'
                ],
                respaldoGatos: [
                    'Mestizo / Doméstico', 'Siamés', 'Persa', 'Maine Coon', 'Bengalí',
                    'Ragdoll', 'Sphynx / Esfinge', 'Azul Ruso', 'British Shorthair', 'Angora Turco', 'Bombay'
                ],

                init() {
                    if (this.especie) {
                        this.cargarRazas(false);
                    }
                },

                get razasFiltradas() {
                    if (!this.raza.trim() || this.razas.includes(this.raza)) {
                        return this.razas;
                    }
                    const termino = this.raza.toLowerCase().trim();
                    return this.razas.filter(r => r.toLowerCase().includes(termino));
                },

                get placeholderRaza() {
                    if (this.modoManual) return 'Escribe la raza o especie específica...';
                    if (this.especie === 'perro') return 'Selecciona o escribe la raza (ej. Labrador, Mestizo)...';
                    if (this.especie === 'gato') return 'Selecciona o escribe la raza (ej. Siamés, Mestizo)...';
                    if (this.especie === 'otro') return 'Selecciona o escribe (ej. Conejo, Erizo, Iguana)...';
                    return 'Primero selecciona una especie';
                },

                seleccionarRaza(valor) {
                    this.raza = valor.trim();
                    this.abiertoRazas = false;
                    this.modoManual = false;
                },

                activarEscrituraManual() {
                    this.modoManual = true;
                    this.abiertoRazas = false;
                    this.raza = '';
                    this.$nextTick(() => {
                        if (this.$refs.inputRaza) {
                            this.$refs.inputRaza.focus();
                        }
                    });
                },

                capitalizar(texto) {
                    return texto.replace(/\b\w/g, l => l.toUpperCase());
                },

                async cargarRazas(limpiarSeleccion = true) {
                    if (limpiarSeleccion) {
                        this.raza = '';
                    }
                    this.razas = [];
                    this.abiertoRazas = false;
                    this.modoManual = false;

                    if (this.especie === 'perro') {
                        this.cargandoRazas = true;
                        try {
                            const res = await fetch('https://dog.ceo/api/breeds/list/all');
                            const data = await res.json();
                            const listaApi = [];

                            for (const [razaPrincipal, subrazas] of Object.entries(data.message)) {
                                if (subrazas.length === 0) {
                                    listaApi.push(this.capitalizar(razaPrincipal));
                                } else {
                                    subrazas.forEach(sub => {
                                        listaApi.push(this.capitalizar(`${sub} ${razaPrincipal}`));
                                    });
                                }
                            }

                            this.razas = ['Mestizo / Criollo', 'Xoloitzcuintle', ...listaApi];
                        } catch (e) {
                            this.razas = this.respaldoPerros;
                        } finally {
                            this.cargandoRazas = false;
                        }
                    } else if (this.especie === 'gato') {
                        this.cargandoRazas = true;
                        try {
                            const res = await fetch('https://api.thecatapi.com/v1/breeds');
                            const data = await res.json();
                            const listaApi = data.map(g => g.name);

                            this.razas = ['Mestizo / Doméstico', ...listaApi];
                        } catch (e) {
                            this.razas = this.respaldoGatos;
                        } finally {
                            this.cargandoRazas = false;
                        }
                    } else if (this.especie === 'otro') {
                        this.razas = ['Conejo', 'Hámster', 'Cuyo', 'Perico / Ninfa', 'Tortuga', 'Hurón', 'Erizo', 'Iguana'];
                    }
                }
            }
        }

        function recortadorFoto() {
                            return {
                                cropper: null,
                                imagenOriginal: null,
                                imagenFinal: null,
                                editandoFoto: false,
                                nombreArchivo: 'mascota.jpg',

                                iniciarCropper() {
                                    this.$nextTick(() => {
                                        const img = this.$refs.imagenCropper;
                                        if (!img) return;

                                        if (this.cropper) {
                                            this.cropper.destroy();
                                            this.cropper = null;
                                        }

                                        img.src = this.imagenOriginal;

                                        this.cropper = new Cropper(img, {
                                            aspectRatio: 16 / 9,
                                            viewMode: 1,
                                            dragMode: 'move',
                                            autoCropArea: 0.9,
                                            responsive: true,
                                            restore: false,
                                            background: false,
                                            minContainerWidth: 200,
                                            minContainerHeight: 320,
                                        });
                                    });
                                },

                                abrirEditor(event) {
                                    const file = event.target.files[0];
                                    if (!file) return;

                                    this.nombreArchivo = file.name.replace(/\.[^/.]+$/, '') + '.jpg';

                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.imagenOriginal = e.target.result;
                                        this.editandoFoto = true;
                                        this.iniciarCropper();
                                    };
                                    reader.readAsDataURL(file);
                                },

                                reabrirEditor() {
                                    if (!this.imagenOriginal) return;
                                    this.editandoFoto = true;
                                    this.iniciarCropper();
                                },
                                zoom(valor) {
                                    if (this.cropper) this.cropper.zoom(valor);
                                },

                                rotar() {
                                    if (this.cropper) this.cropper.rotate(90);
                                },

                                aplicarRecorte() {
                                    if (!this.cropper) return;

                                    const canvas = this.cropper.getCroppedCanvas({
                                        width: 800,
                                        height: 800,
                                        imageSmoothingQuality: 'high',
                                    });

                                    this.imagenFinal = canvas.toDataURL('image/jpeg', 0.88);

                                    // Reemplaza el archivo dentro del input[type="file"] para que Laravel lo reciba normal
                                    canvas.toBlob((blob) => {
                                        const archivoRecortado = new File([blob], this.nombreArchivo, {
                                            type: 'image/jpeg',
                                            lastModified: Date.now(),
                                        });

                                        const dataTransfer = new DataTransfer();
                                        dataTransfer.items.add(archivoRecortado);
                                        this.$refs.inputFoto.files = dataTransfer.files;

                                        this.editandoFoto = false;
                                        this.cropper.destroy();
                                        this.cropper = null;
                                    }, 'image/jpeg', 0.88);
                                },

                                cancelarEdicion() {
                                    this.editandoFoto = false;
                                    if (this.cropper) {
                                        this.cropper.destroy();
                                        this.cropper = null;
                                    }
                                    if (!this.imagenFinal) {
                                        this.$refs.inputFoto.value = '';
                                    }
                                },

                                limpiarFoto() {
                                    this.imagenOriginal = null;
                                    this.imagenFinal = null;
                                    this.editandoFoto = false;
                                    this.$refs.inputFoto.value = '';
                                }
                            }
                        }
    </script>
</x-app-layout>