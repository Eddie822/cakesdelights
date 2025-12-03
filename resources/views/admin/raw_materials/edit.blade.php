<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-3">Editar Materia Prima: {{ $raw_material->name }}</h1>

            <form action="{{ route('admin.raw_materials.update', $raw_material) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nombre de la Materia Prima --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Nombre de la Materia Prima</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $raw_material->name) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Stock Actual (Solo Lectura) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="current_stock">Stock Actual</label>
                        <input type="text" value="{{ number_format($raw_material->current_stock, 2) }} {{ $raw_material->unit }}"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                        <p class="mt-1 text-xs text-indigo-600 font-semibold">El stock solo se actualiza al recibir pedidos o producir.</p>
                    </div>

                    {{-- Unidad de Medida (Solo Lectura, para consistencia) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="unit">Unidad de Medida</label>
                        <input type="text" value="{{ $raw_material->unit }}"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                        <p class="mt-1 text-xs text-gray-500">La unidad ({{ $raw_material->unit }}) no es editable.</p>

                        {{-- Campo oculto para enviar el valor real de la unidad --}}
                        <input type="hidden" name="unit" value="{{ $raw_material->unit }}">
                        {{-- Campo oculto para enviar el stock actual para la validación del controlador --}}
                        <input type="hidden" name="current_stock" value="{{ $raw_material->current_stock }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Stock Mínimo (Editable) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="min_stock">Stock Mínimo de Alerta</label>
                        <input type="number" step="0.01" name="min_stock" id="min_stock" value="{{ old('min_stock', $raw_material->min_stock) }}"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('min_stock') border-red-500 @enderror" min="0" required>
                        @error('min_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Proveedor Principal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="supplier_id">Proveedor Principal</label>
                        <select name="supplier_id" id="supplier_id"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('supplier_id') border-red-500 @enderror">
                            <option value="">-- Sin Asignar --</option>
                            {{-- $suppliers se pasa desde RawMaterialController@edit --}}
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id', $raw_material->supplier_id) == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Proveedor de referencia para futuros pedidos.</p>
                        @error('supplier_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Botones de Acción --}}
                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.raw_materials.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
