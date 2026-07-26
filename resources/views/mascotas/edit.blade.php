<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Mascota: ') }} {{ $mascota->nombre }}
        </h2>
    </x-slot>

    <div class="py-10">
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
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                            <option value="perro" {{ old('especie', $mascota->especie) == 'perro' ? 'selected' : '' }}>Perro</option>
                            <option value="gato" {{ old('especie', $mascota->especie) == 'gato' ? 'selected' : '' }}>Gato</option>
                            <option value="otro" {{ old('especie', $mascota->especie) == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <x-input-error :messages="$errors->get('especie')" class="mt-2" />
                    </div>

                    <!-- Raza -->
                    <div>
                        <x-input-label for="raza" :value="__('Raza (opcional)')" />
                        <x-text-input id="raza" name="raza" type="text" class="block mt-1 w-full" :value="old('raza', $mascota->raza)" />
                        <x-input-error :messages="$errors->get('raza')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Fecha de nacimiento -->
                       

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
                    </div>

                    <!-- Foto Actual y Nueva -->
                    <div>
                        <x-input-label for="foto" :value="__('Foto (opcional)')" />
                        
                        @if($mascota->foto)
                            <div class="my-3">
                                <p class="text-sm text-gray-500 mb-1">Foto actual:</p>
                                <img src="{{ asset('storage/' . $mascota->foto) }}" alt="Foto de la mascota" class="w-24 h-24 object-cover rounded-md shadow-sm border border-gray-200">
                            </div>
                        @endif

                        <input id="foto" name="foto" type="file" accept="image/*"
                               class="block mt-1 w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                      file:bg-primary/10 file:text-primary file:font-medium
                                      hover:file:bg-primary hover:file:text-white file:transition-all file:duration-200">
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
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
</x-app-layout>