<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">
            Iniciar Nuevo Lote de Producción
        </h1>

        <div class="card bg-white shadow-xl rounded-lg p-6 space-y-6">
            <p class="text-gray-600">
                Seleccione la receta y la cantidad de producto final que desea generar.
            </p>

            <form action="{{ route('admin.productions.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Selector de Receta --}}
                <div>
                    <label for="recipe_id" class="block text-sm font-medium text-gray-700 mb-1">Receta a Utilizar</label>
                    <select name="recipe_id" id="recipe_id" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Seleccione una Receta --</option>
                        @foreach($recipes as $recipe)
                            <option value="{{ $recipe->id }}" {{ old('recipe_id') == $recipe->id ? 'selected' : '' }}>
                                {{ $recipe->name }} (Producto: {{ $recipe->product->name ?? 'N/A' }}) - Rendimiento: {{ $recipe->yield }} Und.
                            </option>
                        @endforeach
                    </select>
                    @error('recipe_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    @if($recipes->isEmpty())
                        <p class="mt-1 text-sm text-red-500">No hay recetas definidas. Cree una antes de iniciar la producción.</p>
                    @endif
                </div>

                {{-- Cantidad a Producir --}}
                <div>
                    <label for="quantity_produced" class="block text-sm font-medium text-gray-700 mb-1">
                        Cantidad de Unidades a Producir (Producto Final)
                    </label>
                    <input type="number" name="quantity_produced" id="quantity_produced" required min="1"
                           value="{{ old('quantity_produced', 1) }}"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="mt-1 text-xs text-gray-500">Esta es la cantidad de producto final que desea sumar al stock (ej: 10 pasteles).</p>
                    @error('quantity_produced') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('admin.productions.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-green">
                        <i class="fas fa-play-circle mr-2"></i> Iniciar Producción
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
