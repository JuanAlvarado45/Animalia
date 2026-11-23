<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil de la Clínica') }}
        </h2>
    </x-slot>

    @php
        $urgencias = $errors->any()
            ? old('urgencias_24h')
            : ($veterinaria->urgencias_24h ?? false);
    @endphp

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md p-8">

                <form method="POST" action="{{ route('veterinaria.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="nombre_clinica" :value="__('Nombre de la clínica')" />
                        <x-text-input id="nombre_clinica" name="nombre_clinica" type="text" class="block mt-1 w-full"
                                      :value="old('nombre_clinica', $veterinaria->nombre_clinica ?? '')" required autofocus />
                        <x-input-error :messages="$errors->get('nombre_clinica')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="cedula_profesional" :value="__('Cédula profesional')" />
                        <x-text-input id="cedula_profesional" name="cedula_profesional" type="text" class="block mt-1 w-full"
                                      :value="old('cedula_profesional', $veterinaria->cedula_profesional ?? '')" />
                        <x-input-error :messages="$errors->get('cedula_profesional')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="telefono" :value="__('Teléfono de la clínica')" />
                        <x-text-input id="telefono" name="telefono" type="text" class="block mt-1 w-full"
                                      :value="old('telefono', $veterinaria->telefono ?? '')" required />
                        <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="direccion" :value="__('Dirección')" />
                        <x-text-input id="direccion" name="direccion" type="text" class="block mt-1 w-full"
                                      :value="old('direccion', $veterinaria->direccion ?? '')" required />
                        <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="horario" :value="__('Horario de atención (opcional)')" />
                        <textarea id="horario" name="horario" rows="3"
                                  class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring-primary"
                                  placeholder="Ej. Lunes a viernes de 9:00 a 20:00">{{ old('horario', $veterinaria->horario ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('horario')" class="mt-2" />
                    </div>

                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="urgencias_24h" value="1" {{ $urgencias ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-gray-700">Atendemos urgencias las 24 horas</span>
                    </label>

                    <div class="flex gap-3 pt-4">
                        <a href="{{ route('veterinario.panel') }}" class="flex-1 text-center py-2.5 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                            Cancelar
                        </a>
                        <button type="submit" class="flex-1 py-2.5 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition-all duration-200">
                            Guardar clínica
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>