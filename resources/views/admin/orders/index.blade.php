<x-admin-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold mb-6 border-b pb-2">Órdenes de Clientes</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded-lg shadow-xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha Pedido</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entrega Estimada</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($orders as $order)

                        @php
                            $statusMap = [
                                'pending' => ['text' => 'Pendiente Pago', 'class' => 'bg-yellow-100 text-yellow-800'],
                                'paid' => ['text' => 'Pagado', 'class' => 'bg-blue-100 text-blue-800'],
                                'shipped' => ['text' => 'Enviado', 'class' => 'bg-indigo-100 text-indigo-800'],
                                'delivered' => ['text' => 'Entregado', 'class' => 'bg-green-100 text-green-800'],
                                'canceled' => ['text' => 'Cancelado', 'class' => 'bg-red-100 text-red-800'],
                            ];
                            $status = $statusMap[$order->status] ?? $statusMap['pending'];
                        @endphp

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</td>

                            {{-- Columna Estado --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>

                            {{-- Columna Entrega Estimada --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $order->delivery_date?->format('d/m/Y') ?? 'N/A' }}
                            </td>

                            {{-- Columna Acciones --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">

                                {{-- ACCIÓN: ENVIAR (De Pagado a Enviado) --}}
                                @if($order->status === 'paid')
                                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Confirmar ENVÍO del paquete?');">
                                        @csrf
                                        <input type="hidden" name="status" value="shipped">
                                        <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 rounded px-3 py-1 text-xs">
                                            Enviar
                                        </button>
                                    </form>

                                {{-- ACCIÓN: COMPLETAR (De Enviado a Entregado) --}}
                                @elseif($order->status === 'shipped')
                                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Marcar como ENTREGADO manualmente? Esto actualizará la fecha de entrega.');">
                                        @csrf
                                        <input type="hidden" name="status" value="delivered">
                                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 rounded px-3 py-1 text-xs">
                                            Entregado
                                        </button>
                                    </form>

                                @else
                                    <span class="text-gray-400 text-xs italic">Sin acción</span>
                                @endif

                                {{-- ACCIÓN: ELIMINAR --}}
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('¿Estás seguro de eliminar la orden #{{ $order->id }}? Esta acción es irreversible.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 rounded px-3 py-1 text-xs">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</x-admin-layout>
