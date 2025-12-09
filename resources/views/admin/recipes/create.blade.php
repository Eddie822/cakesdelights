<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-3">Definir Nueva Receta de Producto</h1>

        {{-- Bloque de Errores de Validación (útil para depurar si el controlador rechaza los datos) --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-600 text-red-800 rounded-lg shadow-md">
                <p class="font-bold mb-2">No se pudo guardar la receta. Por favor revisa:</p>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario oculto que recibe los datos del JS y los envía a Laravel --}}
        <form id="recipe-form" action="{{ route('admin.recipes.store') }}" method="POST" class="hidden">
            @csrf
            {{-- Los inputs se generan dinámicamente aquí --}}
        </form>

        {{-- INTEGRACIÓN DEL COMPONENTE LIVEWIRE --}}
        {{-- Este componente maneja la lógica visual de agregar ingredientes --}}
        @livewire('admin.create-recipe')
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
             // Escucha el evento 'submit-recipe-form' que emite el componente Livewire al guardar
            Livewire.on('submit-recipe-form', (payload) => {
                const form = document.getElementById('recipe-form');

                // Reiniciar el formulario (manteniendo solo el token CSRF)
                form.innerHTML = `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">`;

                // 1. Agregar campos del Encabezado de la Receta
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="product_id" value="${payload.product_id}">`);
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="name" value="${payload.name}">`);
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="yield" value="${payload.yield}">`);

                // 2. Agregar campos de Ingredientes (Array)
                // Se recorre el array enviado por Livewire y se crean inputs con notación de array para Laravel
                payload.ingredients.forEach((item, index) => {
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="ingredients[${index}][raw_material_id]" value="${item.raw_material_id}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="ingredients[${index}][quantity_required]" value="${item.quantity_required}">`);
                });

                // 3. Enviar el formulario tradicional al controlador Laravel
                form.submit();
            });
        });
    </script>
    @endpush
</x-admin-layout>
