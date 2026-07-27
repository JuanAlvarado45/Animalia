<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Mascotas') }}
        </h2>
    </x-slot>

    <!-- Estado Global con Alpine.js -->
    <div x-data="qrComponent()" class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 bg-primary/10 border border-primary/30 text-primary-dark rounded-xl p-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($mascotas as $mascota)
                    <div class="group bg-white rounded-xl shadow-md overflow-hidden border-2 border-transparent hover:border-primary hover:shadow-xl transition-all duration-300 flex flex-col">

                        <div class="h-40 bg-primary/10 flex items-center justify-center overflow-hidden">
                            @if ($mascota->foto)
                                <img src="{{ Storage::url($mascota->foto) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-primary/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.5 0 4.5-2.24 4.5-5S14.5 2 12 2 7.5 4.24 7.5 7s2 5 4.5 5zM5 22c0-4.5 3-8 7-8s7 3.5 7 8" />
                                </svg>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $mascota->nombre }}</h3>
                                    <p class="text-sm text-gray-500">{{ ucfirst($mascota->especie) }}@if($mascota->raza) · {{ $mascota->raza }} @endif</p>
                                </div>
                                @if ($mascota->extraviado)
                                    <span class="text-xs font-medium bg-red-100 text-red-600 px-2 py-1 rounded-full">Extraviado</span>
                                @else
                                    <span class="text-xs font-medium bg-green-100 text-green-700 px-2 py-1 rounded-full">Al día</span>
                                @endif
                            </div>

                            <div class="flex-grow"></div>

                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('mascotas.show', $mascota) }}" class="flex-1 text-center text-sm py-2 rounded-md border border-gray-400 text-gray-600 font-medium hover:bg-gray-600 hover:text-white hover:border-gray-600 transition-all duration-200">
                                    Ver expediente
                                </a>
                                
                                <button type="button" 
                                    @click="gestionarQR({{ $mascota->id }}, '{{ $mascota->nombre }}', '{{ $mascota->qr_path ? Storage::url($mascota->qr_path) : '' }}')" 
                                    class="flex-1 text-center text-sm py-2 rounded-md bg-primary/10 text-primary font-medium hover:bg-primary hover:text-white transition cursor-pointer">
                                    Ver QR
                                </button>
                            </div>

                            <div class="flex gap-2 mt-2 pt-2 border-t border-gray-100">
                                <a href="{{ route('mascotas.edit', $mascota) }}" class="flex-1 text-center text-sm py-2 rounded-md border border-yellow-400 text-yellow-600 font-medium hover:bg-yellow-500 hover:text-white hover:border-yellow-500 transition-all duration-200">Editar</a>
                                
                                <!-- Formulario de eliminación -->
                                <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center text-sm py-2 rounded-md border border-red-400 text-red-500 font-medium hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200" onclick="return confirm('¿Seguro que deseas eliminarla?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('mascotas.create') }}" class="flex flex-col items-center justify-center gap-3 bg-white rounded-xl shadow-md border-2 border-dashed border-gray-300 hover:border-primary text-gray-400 hover:text-primary transition-all duration-300 min-h-[280px]">
                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                    <span class="font-medium">Registrar nueva mascota</span>
                </a>

            </div>
        </div>

        <!-- MODAL PARA MOSTRAR Y DESCARGAR EL CÓDIGO QR -->
        <template x-teleport="body">
            <div x-show="mostrarModalQR" style="display: none;" class="fixed inset-0 z-[9999] overflow-y-auto">
                <div x-show="mostrarModalQR" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" @click="mostrarModalQR = false"></div>

                <div class="flex items-center justify-center min-h-screen p-4 z-50 relative">
                    <div x-show="mostrarModalQR" 
                        x-transition:enter="ease-out duration-300" 
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                        x-transition:leave="ease-in duration-200" 
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                        class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center transform transition-all">
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Código QR Único</h3>
                        <p class="text-xs text-gray-500 mb-4">Mascota: <span class="font-bold text-gray-700" x-text="nombreMascota"></span></p>

                        <!-- Contenedor del QR. -->
                        <div class="flex justify-center items-center my-4 p-4 bg-gray-50 rounded-xl border border-gray-100 shadow-inner min-h-[200px] relative">
                            
                            <div x-show="isGenerating" class="absolute inset-0 flex items-center justify-center bg-gray-50/80 rounded-xl z-10">
                                <span class="text-sm text-gray-500 animate-pulse">Generando...</span>
                            </div>

                            <img x-show="qrDataUrl !== ''" :src="qrDataUrl" class="w-[180px] h-[180px]" alt="QR de la mascota">
                            <div id="qrcodeContainer" style="display:none;"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mt-6">
                            <button @click="descargarJPG()" type="button" class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg border border-emerald-500 text-emerald-600 font-semibold text-xs hover:bg-emerald-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Guardar JPG
                            </button>
                            <button @click="descargarPDF()" type="button" class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-lg bg-primary text-white font-semibold text-xs hover:bg-primary-dark transition shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Descargar PDF
                            </button>
                        </div>

                        <button @click="mostrarModalQR = false" type="button" class="mt-4 text-xs font-medium text-gray-400 hover:text-gray-600 transition">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        function qrComponent() {
            return {
                mostrarModalQR: false,
                nombreMascota: '',
                qrDataUrl: '',
                isGenerating: false,
                
                // NUEVO: Diccionario para recordar qué mascotas ya tienen su QR en esta sesión
                qrsMemoria: {},

                gestionarQR(id, nombre, urlGuardada) {
                    this.nombreMascota = nombre;
                    
                    // Comprobamos si la ruta ya viene de la BD (urlGuardada) 
                    // O si la acabamos de generar y la tenemos en la memoria (qrsMemoria)
                    const rutaDefinitiva = this.qrsMemoria[id] || urlGuardada;

                    // Si ya existe una ruta definitiva, no preguntamos, abrimos directo el modal
                    if (rutaDefinitiva !== '') {
                        this.qrDataUrl = rutaDefinitiva;
                        this.mostrarModalQR = true;
                        return;
                    }

                    // Si no existe ninguna de las dos, preguntamos
                   const urlMascota = `${window.location.origin}/qr/${id}`;

                    Swal.fire({
                        title: '¡Generar Código QR Único!',
                        text: `Vas a crear el código QR oficial para ${nombre}. Este identificador será permanente y se guardará en su expediente.`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#047857',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Sí, generar y guardar',
                        cancelButtonText: 'Cancelar',
                        customClass: { popup: 'rounded-2xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.generarYGuardarQR(id, urlMascota);
                        }
                    });
                },

                generarYGuardarQR(id, urlBase) {
                    this.mostrarModalQR = true;
                    this.isGenerating = true;
                    this.qrDataUrl = ''; 
                    
                    const container = document.getElementById('qrcodeContainer');
                    container.innerHTML = ''; 

                    new QRCode(container, {
                        text: urlBase,
                        width: 400, 
                        height: 400,
                        colorDark: "#111827",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });

                    setTimeout(() => {
                        const canvas = container.querySelector('canvas');
                        const base64Image = canvas.toDataURL("image/png");

                        fetch(`/mascotas/${id}/guardar-qr`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ qr_image: base64Image })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.qrDataUrl = data.qr_url;
                                this.isGenerating = false;
                                
                                // NUEVO: Guardamos la URL devuelta en nuestra "memoria" temporal para ese ID
                                this.qrsMemoria[id] = data.qr_url;
                                
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'QR guardado exitosamente',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error al guardar el QR:', error);
                            this.isGenerating = false;
                            Swal.fire('Error', 'No se pudo guardar el QR en el servidor.', 'error');
                        });

                    }, 250);
                },

                descargarJPG() {
                    if (!this.qrDataUrl) return;
                    const a = document.createElement('a');
                    a.href = this.qrDataUrl;
                    a.download = `QR_${this.nombreMascota}.jpg`;
                    a.click();
                },

                async descargarPDF() {
                    if (!this.qrDataUrl) return;
                    
                    let imageForPdf = this.qrDataUrl;

                    if (this.qrDataUrl.startsWith('http') || this.qrDataUrl.startsWith('/')) {
                       imageForPdf = await this.getBase64ImageFromUrl(this.qrDataUrl);
                    }

                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF();
                    doc.setFont("helvetica", "bold");
                    doc.setFontSize(22);
                    doc.setTextColor(17, 24, 39);
                    doc.text(`Identificador QR: ${this.nombreMascota}`, 105, 40, { align: 'center' });
                    doc.setFont("helvetica", "normal");
                    doc.setFontSize(14);
                    doc.setTextColor(107, 114, 128);
                    doc.text("Animalía - Plataforma Preventiva Veterinaria", 105, 50, { align: 'center' });
                    
                    doc.addImage(imageForPdf, 'PNG', 65, 70, 80, 80);
                    doc.save(`QR_${this.nombreMascota}.pdf`);
                },

                async getBase64ImageFromUrl(imageUrl) {
                    const res = await fetch(imageUrl);
                    const blob = await res.blob();
                    return new Promise((resolve, reject) => {
                        const reader  = new FileReader();
                        reader.addEventListener("load", function () {
                            resolve(reader.result);
                        }, false);
                        reader.onerror = () => reject(this);
                        reader.readAsDataURL(blob);
                    });
                }
            }
        }
    </script>
</x-app-layout>