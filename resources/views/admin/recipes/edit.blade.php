<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">
            Editar Receta: {{ $recipe->name }}
        </h1>

        <div class="card bg-white shadow-xl rounded-lg p-6 space-y-6">
            {{-- Mensajes de error generales --}}
            @if(session('error'))
                <div class="p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.recipes.update', $recipe) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre de la Receta --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Receta</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $recipe->name) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Producto Final --}}
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Producto Final</label>
                        <select name="product_id" id="product_id" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Seleccione un Producto --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id', $recipe->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Nota: Cambiar esto asignará la receta a otro producto.</p>
                        @error('product_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Rendimiento --}}
                    <div class="col-span-1">
                        <label for="yield" class="block text-sm font-medium text-gray-700 mb-1">Rendimiento (Unidades)</label>
                        <input type="number" name="yield" id="yield" required min="1" value="{{ old('yield', $recipe->yield) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('yield') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Detalles de la Receta (Ingredientes) --}}
                <div class="border-t pt-4 mt-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Ingredientes Requeridos</h2>
                    <div id="ingredients-container" class="space-y-4">
                        {{--
                            Lógica de carga:
                            1. Si hay old('ingredients') (error de validación), usa eso.
                            2. Si no, usa los ingredientes guardados en la BD ($recipe->ingredients).
                            3. Si es una receta nueva/vacía (raro en edit), muestra una fila vacía.
                        --}}
                        @if(old('ingredients'))
                            @foreach(old('ingredients') as $key => $ingredient)
                                @include('admin.recipes.partials.ingredient-row', ['key' => $key, 'rawMaterials' => $rawMaterials, 'ingredient' => $ingredient])
                            @endforeach
                        @elseif($recipe->ingredients->isNotEmpty())
                            @foreach($recipe->ingredients as $key => $detail)
                                {{-- Pasamos los datos del modelo al formato que espera el partial --}}
                                @include('admin.recipes.partials.ingredient-row', [
                                    'key' => $key,
                                    'rawMaterials' => $rawMaterials,
                                    'ingredient' => [
                                        'raw_material_id' => $detail->raw_material_id,
                                        'quantity_required' => $detail->quantity_required
                                    ]
                                ])
                            @endforeach
                        @else
                            @include('admin.recipes.partials.ingredient-row', ['key' => 0, 'rawMaterials' => $rawMaterials])
                        @endif
                    </div>

                    <button type="button" id="add-ingredient" class="mt-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition">
                        <i class="fas fa-plus mr-2"></i> Agregar Ingrediente
                    </button>
                    @error('ingredients') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('admin.recipes.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-indigo">
                        <i class="fas fa-save mr-2"></i> Actualizar Receta
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('ingredients-container');
            const addButton = document.getElementById('add-ingredient');

            // Calculamos el índice inicial basado en los elementos existentes
            let key = {{ count(old('ingredients', $recipe->ingredients)) }};
            // Si la cuenta es 0 pero hay un elemento visible (el default), ajustamos key a 1
            if (key === 0 && container.children.length > 0) key = 1;

            // Función para generar el HTML de una nueva fila
            function getIngredientRowHtml(currentKey) {
                const rawMaterialsOptions = `
                    @foreach($rawMaterials as $material)
                        <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                    @endforeach
                `;

                // Debe coincidir con la estructura de resources/views/admin/recipes/partials/ingredient-row.blade.php
                return `
                    <div class="grid grid-cols-6 gap-3 items-end ingredient-row">
                        <div class="col-span-4">
                            <label for="material-${currentKey}" class="block text-xs font-medium text-gray-500 mb-1">Materia Prima</label>
                            <select name="ingredients[${currentKey}][raw_material_id]" id="material-${currentKey}" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Seleccione Materia Prima --</option>
                                ${rawMaterialsOptions}
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label for="quantity-${currentKey}" class="block text-xs font-medium text-gray-500 mb-1">Cantidad</label>
                            <input type="number" step="0.001" name="ingredients[${currentKey}][quantity_required]" id="quantity-${currentKey}" required min="0.001" value="1"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="col-span-1">
                            <button type="button" class="remove-ingredient px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 transition w-full">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }

            function addIngredientRow() {
                // CORRECCIÓN: Usar insertAdjacentHTML para insertar el string HTML directamente
                // Esto evita problemas con nodos de texto vacíos creados por firstChild
                container.insertAdjacentHTML('beforeend', getIngredientRowHtml(key));
                key++;
            }

            addButton.addEventListener('click', addIngredientRow);

            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-ingredient')) {
                    const row = e.target.closest('.ingredient-row');
                    if (container.querySelectorAll('.ingredient-row').length > 1) {
                        row.remove();
                    } else {
                        alert('La receta debe tener al menos un ingrediente.');
                    }
                }
            });
        });
    </script>
</x-admin-layout>
