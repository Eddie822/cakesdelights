<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-3">Añadir Nueva Materia Prima</h1>

            <form action="{{ route('admin.raw_materials.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Nombre de la Materia Prima --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Nombre de la Materia Prima (Ej: Harina de Trigo)</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Stock Inicial (FORZADO A CERO Y DE SÓLO LECTURA) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="current_stock">Stock Inicial</label>
                        <input type="number" step="0.01" name="current_stock" id="current_stock" value="0"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed" min="0" required readonly>
                        <p class="mt-1 text-xs text-indigo-600 font-semibold">El stock inicial se establece en 0. Para agregar inventario, use el módulo "Pedidos Materia Prima" después de crear el material.</p>
                        @error('current_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Unidad de Medida --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="unit">Unidad de Medida</label>
                        <select name="unit" id="unit"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('unit') border-red-500 @enderror" required>
                            <option value="">Selecciona unidad</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                            @endforeach
                        </select>
                        @error('unit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Stock Mínimo (Alerta) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="min_stock">Stock Mínimo de Alerta</label>
                        <input type="number" step="0.01" name="min_stock" id="min_stock" value="{{ old('min_stock', 10) }}"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('min_stock') border-red-500 @enderror" min="0" required>
                        @error('min_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Proveedor Principal --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="supplier_id">Proveedor Principal</label>
                        <select name="supplier_id" id="supplier_id"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('supplier_id') border-red-500 @enderror">
                            <option value="">-- Sin Asignar --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
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
                        Guardar Material
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
