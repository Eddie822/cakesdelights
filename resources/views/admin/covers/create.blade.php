<x-admin-layout>
    <form action="{{ route('admin.covers.store') }}"
    method="POST"
    enctype="multipart/form-data"
    >
        @csrf
        <figure class="relative mb-5">

            {{-- Botón flotante para cambiar la imagen --}}
            <div class="absolute top-4 right-4 z-10">
                <label
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full bg-white/80 dark:bg-gray-900/80 text-gray-700 dark:text-gray-300 shadow-md cursor-pointer backdrop-blur-sm transition-all hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600">
                    <i class="fas fa-camera"></i>
                    <span class="hidden sm:inline">Cambiar imagen</span>
                    <input type="file" accept="image/*" class="hidden" name="image" onchange="previewImage(event, '#imgPreview')">
                </label>
            </div>
            <div class="flex justify-center items-center h-full">
                <img class="w-1/2 h-full object-cover object-center rounded-lg shadow-md"
                    src="{{ asset('img/no-image.png') }}"
                    alt="Imagen de portada por defecto"
                    id="imgPreview">
            </div>
        </figure>

        <div class="mb-4">
            <x-label>
                Titulo de la portada
            </x-label>

            <x-input class="w-full" value="{{ old('title') }}" name="title" placeholder="Ingrese un titulo" />

        </div>

        <div class="mb-4">
              <x-label>
                Fecha de incio
            </x-label>

            <x-input type="date" name="start_at"  class="w-full" value="{{ old('start_at', now()->format('Y-m-d')) }}"/>

        </div>

        <div class="mb-4">
              <x-label>
                Fecha fin (opcional)
            </x-label>

            <x-input name="end_at" type="date" class="w-full" value="{{ old('end_at')}}"  />
        </div>

        <div class="mb-4 flex space-x-2">
            <label>
                <x-input type="radio" name="is_active" value="1"
                checked/>
                Activo
            </label>
            <label>
                <x-input type="radio" name="is_active" value="0" />
                Inactivo
            </label>
        </div>

        <div class="flex">
            <x-button>
                Crear portada
            </x-button>
        </div>
    </form>

    @push('js')
        <script>
            function previewImage(event, querySelector) {

                //Recuperamos el input que desencadeno la acción
                let input = event.target;

                //Recuperamos la etiqueta img donde cargaremos la imagen
                let imgPreview = document.querySelector(querySelector);

                // Verificamos si existe una imagen seleccionada
                if (!input.files.length) return

                //Recuperamos el archivo subido
                let file = input.files[0];

                //Creamos la url
                let objectURL = URL.createObjectURL(file);

                //Modificamos el atributo src de la etiqueta img
                imgPreview.src = objectURL;

            }
        </script>
    @endpush
</x-admin-layout>
