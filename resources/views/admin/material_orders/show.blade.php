<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6 sm:p-8">

            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 flex justify-between items-center">
                Detalle del Pedido #{{ $material_order->id }}

                {{-- Botón de regreso --}}
                <a href="{{ route('admin.material_orders.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Listado
                </a>
            </h1>

            {{-- 1. INFORMACIÓN GENERAL Y ESTADO --}}
            @php
                $statusMap = [
                    'pending' => ['text' => 'Pendiente', 'class' => 'bg-blue-500'],
                    'ordered' => ['text' => 'Ordenado', 'class' => 'bg-yellow-500'],
                    'received' => ['text' => 'Recibido', 'class' => 'bg-green-500'],
                    'canceled' => ['text' => 'Cancelado', 'class' => 'bg-gray-500'],
                ];
                $status = $statusMap[$material_order->status] ?? $statusMap['pending'];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 bg-gray-50 rounded-lg border">

                {{-- Proveedor --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Proveedor</p>
                    <p class="text-lg font-medium text-gray-900">{{ $material_order->supplier->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ $material_order->supplier->email ?? '' }}</p>
                </div>

                {{-- Estatus --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Estado</p>
                    <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full text-white {{ $status['class'] }}">
                        {{ $status['text'] }}
                    </span>
                </div>

                {{-- Fechas y Costo --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Fecha de Pedido</p>
                    <p class="text-sm text-gray-900">{{ $material_order->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-2">Entrega Esperada</p>
                    <p class="text-sm text-gray-900">{{ $material_order->expected_delivery_date?->format('d/m/Y') ?? 'N/A' }}</p>
                </div>
            </div>

            {{-- 2. TABLA DE ÍTEMS DEL PEDIDO --}}
            <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Materiales Solicitados</h2>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">Material</th>
                            <th scope="col" class="px-6 py-3 text-center">Unidad</th>
                            <th scope="col" class="px-6 py-3 text-right">Cantidad Pedida</th>
                            <th scope="col" class="px-6 py-3 text-right">Costo Unitario</th>
                            <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($material_order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ $item->material->name ?? 'Material No Encontrado' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-600">
                                    {{ $item->material->unit ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700">
                                    {{ number_format($item->quantity_ordered, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700">
                                    ${{ number_format($item->unit_cost, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-gray-900">
                                    ${{ number_format($item->quantity_ordered * $item->unit_cost, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Fila de Total --}}
            <div class="flex justify-end mt-4">
                <div class="text-right p-4 bg-gray-50 rounded-lg border-t-4 border-indigo-600">
                    <p class="text-xl font-bold text-gray-800">Costo Total: ${{ number_format($material_order->total_cost, 2) }}</p>
                </div>
            </div>

            {{-- 3. ACCIÓN DE RECEPCIÓN (Si está en estado 'ordered' o 'pending') --}}
            @if ($material_order->status === 'ordered' || $material_order->status === 'pending')
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-xl font-semibold text-indigo-700 mb-4">Control de Almacén</h3>
                    <form action="{{ route('admin.material_orders.receive', $material_order) }}" method="POST" onsubmit="return confirm('ATENCIÓN: ¿Deseas confirmar la recepción de este pedido? Esto sumará todos los materiales al stock.');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition shadow-lg">
                            <i class="fas fa-check-circle mr-2"></i> CONFIRMAR RECEPCIÓN Y ACTUALIZAR STOCK
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-admin-layout>
