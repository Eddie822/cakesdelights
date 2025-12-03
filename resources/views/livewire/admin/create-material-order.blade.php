<div class="card bg-white shadow-xl rounded-lg p-6 sm:p-8">
    {{-- El formulario llama al método saveOrder en el componente Livewire --}}
    <form wire:submit.prevent="saveOrder" class="space-y-8">

        {{-- SECCIÓN 1: ENCABEZADO DEL PEDIDO --}}
        <div class="border-b pb-6 space-y-6">
            <h2 class="text-xl font-bold text-gray-800">Detalles del Pedido</h2>

            {{-- Selector de Proveedor --}}
            <div>
                <x-label for="supplierId" class="mb-1">Proveedor</x-label>
                <x-select wire:model.live="supplierId" id="supplierId" class="w-full" required>
                    <option value="">-- Seleccione un Proveedor --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </x-select>
                @error('supplierId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Costo Total (Solo Lectura) --}}
            <div>
                <x-label for="orderTotal" class="mb-1 text-lg font-bold">Costo Total Estimado</x-label>
                <x-input type="text" id="orderTotal" value="${{ number_format($orderTotal, 2) }}" readonly
                    class="w-full bg-gray-100 font-bold text-xl text-indigo-700 cursor-not-allowed border-gray-300" />
            </div>
        </div>

        {{-- SECCIÓN 2: ÍTEMS DEL PEDIDO (Tabla Dinámica) --}}
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex justify-between items-center">
                Materia Prima a Comprar
                <x-button type="button" wire:click="addItem" class="btn-green">
                    <i class="fas fa-plus mr-2"></i> Añadir Material
                </x-button>
            </h2>

            <div class="overflow-x-auto shadow-md rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="px-3 py-3 text-left">Material</th>
                            <th class="px-3 py-3 text-center">Unidad</th>
                            <th class="px-3 py-3 text-right">Cantidad</th>
                            <th class="px-3 py-3 text-right">Costo Unitario ($)</th>
                            <th class="px-3 py-3 text-right">Subtotal ($)</th>
                            <th class="px-3 py-3 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">

                        {{-- Bucle sobre los ítems del pedido --}}
                        @forelse ($items as $index => $item)
                            <tr wire:key="item-{{ $index }}">
                                {{-- Selector de Material --}}
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <x-select wire:model.live="items.{{ $index }}.raw_material_id"
                                        class="w-full text-sm @error('items.'.$index.'.raw_material_id') border-red-500 @enderror" required>
                                        <option value="">Seleccione Material</option>
                                        @foreach($availableMaterials as $material)
                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                                        @endforeach
                                    </x-select>
                                    @error('items.'.$index.'.raw_material_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </td>

                                {{-- Unidad (Solo Lectura) --}}
                                <td class="px-3 py-3 whitespace-nowrap text-center text-gray-600 font-semibold">
                                    {{ $item['material_unit'] ?: '---' }}
                                </td>

                                {{-- Cantidad Pedida --}}
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <x-input type="number" step="0.01" min="0.01" wire:model.live="items.{{ $index }}.quantity_ordered"
                                        class="w-24 text-sm text-right @error('items.'.$index.'.quantity_ordered') border-red-500 @enderror" required />
                                    @error('items.'.$index.'.quantity_ordered') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </td>

                                {{-- Costo Unitario --}}
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <x-input type="number" step="0.01" min="0.01" wire:model.live="items.{{ $index }}.unit_cost"
                                        class="w-24 text-sm text-right @error('items.'.$index.'.unit_cost') border-red-500 @enderror" required />
                                    @error('items.'.$index.'.unit_cost') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </td>

                                {{-- Subtotal (Solo Lectura) --}}
                                <td class="px-3 py-3 whitespace-nowrap text-right font-bold text-gray-800">
                                    ${{ number_format($item['subtotal'], 2) }}
                                </td>

                                {{-- Botón de Eliminar --}}
                                <td class="px-3 py-3 whitespace-nowrap text-center">
                                    @if(count($items) > 1)
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                                class="text-red-600 hover:text-red-800 transition">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                                    No hay materiales en el pedido. Agregue uno para comenzar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Fila de Total --}}
            <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                <div class="text-right font-extrabold text-xl">
                    TOTAL DEL PEDIDO: ${{ number_format($orderTotal, 2) }}
                </div>
            </div>
        </div>

        {{-- SECCIÓN 3: BOTONES DE ACCIÓN --}}
        <div class="pt-4 flex justify-end space-x-3 border-t border-gray-200">
            <a href="{{ route('admin.material_orders.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                Cancelar
            </a>

            <x-button type="submit" class="btn-indigo">
                Enviar Pedido
            </x-button>
        </div>
    </form>
</div>
