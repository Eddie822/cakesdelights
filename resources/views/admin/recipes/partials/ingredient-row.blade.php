{{-- Este partial renderiza una fila para seleccionar un ingrediente y su cantidad --}}
<div class="grid grid-cols-6 gap-3 items-end ingredient-row">
    {{-- Selector de Materia Prima --}}
    <div class="col-span-4">
        <label for="material-{{ $key }}" class="block text-xs font-medium text-gray-500 mb-1">Materia Prima</label>
        <select name="ingredients[{{ $key }}][raw_material_id]" id="material-{{ $key }}" required
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">-- Seleccione Materia Prima --</option>
            @foreach($rawMaterials as $material)
                <option value="{{ $material->id }}"
                        {{ (isset($ingredient['raw_material_id']) && $ingredient['raw_material_id'] == $material->id) || old("ingredients.{$key}.raw_material_id") == $material->id ? 'selected' : '' }}>
                    {{ $material->name }} ({{ $material->unit }})
                </option>
            @endforeach
        </select>
        @error("ingredients.{$key}.raw_material_id") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Input de Cantidad --}}
    <div class="col-span-1">
        <label for="quantity-{{ $key }}" class="block text-xs font-medium text-gray-500 mb-1">Cantidad</label>
        <input type="number" step="0.001" name="ingredients[{{ $key }}][quantity_required]" id="quantity-{{ $key }}" required min="0.001"
               value="{{ isset($ingredient['quantity_required']) ? $ingredient['quantity_required'] : old("ingredients.{$key}.quantity_required", 1) }}"
               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error("ingredients.{$key}.quantity_required") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Botón de Eliminar --}}
    <div class="col-span-1">
        <button type="button" class="remove-ingredient px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 transition w-full">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>
