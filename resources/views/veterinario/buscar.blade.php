<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buscar Mascota') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md p-8">

                <p class="text-sm text-gray-500 mb-5">
                    Escanea el QR de la placa y pega aquí el enlace, o escribe el código de la mascota.
                </p>

                <form method="POST" action="{{ route('expediente.buscar') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="codigo" :value="__('Código o enlace del QR')" />
                        <x-text-input id="codigo" name="codigo" type="text" class="block mt-1 w-full"
                                      :value="old('codigo')" required autofocus />
                        <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full py-2.5 rounded-md bg-primary text-white font-semibold hover:bg-primary-dark transition-all duration-200">
                        Consultar expediente
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>