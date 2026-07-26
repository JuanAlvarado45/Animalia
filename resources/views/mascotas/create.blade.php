<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Mascota') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-md p-8">

                <form method="POST" action="{{ route('mascotas.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <!-- Nombre -->
                    <div>
                        <x-input-label for="nombre" :value="__('Nombre de la mascota')" />
                        <x-text-input id="nombre" name="nombre" type="text" class="block mt-1 w-full" :value="old('nombre')" required autofocus />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- Especie -->
                    <div>
                        <x-input-label for="especie" :value="__('Especie')" />
                        <select id="especie" name="especie" required
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Selecciona una opción</option>
                            <option value="perro" {{ old('especie') == 'perro' ? 'selected' : '' }}>Perro</option>
                            <option value="gato" {{ old('especie') == 'gato' ? 'selected' : '' }}>Gato</option>
                            <option value="otro" {{ old('especie') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <x-input-error :messages="$errors->get('especie')" class="mt-2" />
                    </div>

                    <!-- Raza -->
                    <div>
                        <x-input-label for="raza" :value="__('Raza (opcional)')" />
                        <x-text-input id="raza" name="raza" type="text" class="block mt-1 w-full" :value="old('raza')" />
                        <x-input-error :messages="$errors->get('raza')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Fecha de nacimiento -->
                        <div>
                            <x-input-label for="fecha_nacimiento" :value="__('Fecha de nacimiento')" />
                            <x-text-input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="block mt-1 w-full" :value="old('fecha_nacimiento')" required/>
                            <x-input-error :messages="$errors->get('fecha_nacimiento')" class="mt-2" />
                        </div>

                        <!-- Sexo -->
                        <div>
                            <x-input-label for="sexo" :value="__('Sexo')" />
                            <select id="sexo" name="sexo" required
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary">
                                <option value="">Selecciona</option>
                                <option value="macho" {{ old('sexo') == 'macho' ? 'selected' : '' }}>Macho</option>
                                <option value="hembra" {{ old('sexo') == 'hembra' ? 'selected' : '' }}>Hembra</option>
                            </select>
                            <x-input-error :messages="$errors->get('sexo')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Foto -->
                    <div>
                        <x-input-label for="foto" :value="__('Foto (opcional)')" />
                        <input id="foto" name="foto" type="file" accept="image/*"
                               class="block mt-1 w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                      file:bg-primary/10 file:text-primary file:font-medium
                                      hover:file:bg-primary hover:file:text-white file:transition-all file:duration-200">
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3 pt-4">
                        <a href="{{ route('mascotas.index') }}" class="flex-1 text-center py-2.5 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="flex-1 py-2.5 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition-all duration-200 hover:scale-105">
                            {{ __('Registrar mascota') }}
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>