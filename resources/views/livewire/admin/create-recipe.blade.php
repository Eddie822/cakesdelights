<div class="card bg-white shadow-xl rounded-lg p-6 sm:p-8">
    <form wire:submit.prevent="saveRecipe" class="space-y-8">

        {{-- SECCIÓN 1: DATOS GENERALES --}}
        <div class="border-b pb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Información del Producto</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- 1. Filtro: Familia --}}
                <div>
                    <x-label for="familyId" class="mb-1">1. Selecciona la Familia</x-label>
                    <x-select wire:model.live="familyId" id="familyId" class="w-full">
                        <option value="">-- Seleccione Familia --</option>
                        @foreach($families as $family)
                            <option value="{{ $family->id }}">{{ $family->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                {{-- 2. Producto Final (Filtrado) --}}
                <div>
                    <x-label for="productId" class="mb-1">2. Producto Final (Se fabricará)</x-label>
                    <x-select wire:model.live="productId" id="productId" class="w-full" required>
                        @if($products->isEmpty())
                            <option value="">{{ $familyId ? '-- No hay productos disponibles sin receta --' : '-- Primero selecciona una familia --' }}</option>
                        @else
                            <option value="">-- Seleccione un Producto --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        @endif
                    </x-select>
                    @error('productId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- 3. Nombre de la Receta --}}
                <div>
                    <x-label for="recipeName" class="mb-1">Nombre de la Receta</x-label>
                    <x-input type="text" wire:model.live="recipeName" id="recipeName"
                        placeholder="Ej: Receta Base de Pastel de Vainilla" required class="w-full"/>
                    @error('recipeName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- 4. Rendimiento --}}
                <div>
                    <x-label for="recipeYield" class="mb-1">Rendimiento (Unidades)</x-label>
                    <x-input type="number" min="1" wire:model.live="recipeYield" id="recipeYield"
                        placeholder="1" required class="w-full"/>
                    <p class="mt-1 text-xs text-gray-500">Cuántos productos crea esta receta.</p>
                    @error('recipeYield') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- SECCIÓN 2: DETALLES DE INGREDIENTES --}}
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex justify-between items-center">
                Ingredientes Requeridos
                <x-button type="button" wire:click="addIngredient" class="bg-green-600 hover:bg-green-700 text-white">
                    <i class="fas fa-plus mr-2"></i> Añadir Ingrediente
                </x-button>
            </h2>

            <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="px-3 py-3 text-left">Materia Prima</th>
                            <th class="px-3 py-3 text-right">Cantidad Requerida</th>
                            <th class="px-3 py-3 text-center">Unidad</th>
                            <th class="px-3 py-3 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($ingredients as $index => $ingredient)
                            <tr wire:key="ingredient-{{ $index }}">
                                {{-- Selector --}}
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <x-select wire:model.live="ingredients.{{ $index }}.raw_material_id" class="w-full text-sm" required>
                                        <option value="">Seleccione Material</option>
                                        @foreach($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                                        @endforeach
                                    </x-select>
                                    @error('ingredients.'.$index.'.raw_material_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </td>

                                {{-- Cantidad --}}
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <x-input type="number" step="0.001" min="0.001" wire:model.live="ingredients.{{ $index }}.quantity_required" class="w-24 text-sm text-right" required />
                                    @error('ingredients.'.$index.'.quantity_required') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </td>

                                {{-- Unidad (Lectura) --}}
                                <td class="px-3 py-3 whitespace-nowrap text-center text-gray-600 font-semibold">
                                    {{ $ingredient['material_unit'] ?: '---' }}
                                </td>

                                {{-- Eliminar --}}
                                <td class="px-3 py-3 whitespace-nowrap text-center">
                                    @if(count($ingredients) > 1)
                                        <button type="button" wire:click="removeIngredient({{ $index }})" class="text-red-600 hover:text-red-800 transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-gray-500">
                                    Esta receta no tiene ingredientes. Agregue uno para comenzar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BOTONES --}}
        <div class="pt-4 flex justify-end space-x-3 border-t border-gray-200">
            <a href="{{ route('admin.recipes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                Cancelar
            </a>

            <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white">
                Guardar Receta
            </x-button>
        </div>
    </form>
</div>
