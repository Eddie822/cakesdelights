<x-app-layout>
    <div class="min-h-screen bg-[#f3e7d9] py-10">
        <div class="container mx-auto px-6 max-w-4xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b-4 border-pink-500 pb-2">
                Historial de Mis Pedidos
            </h1>

            {{-- Mensajes de Éxito o Error --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 rounded-lg shadow-md">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-600 text-red-800 rounded-lg shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Listado de Pedidos --}}
            @forelse ($orders as $order)
                @php
                    // Mapeo de estados para estilos visuales
                    $statusMap = [
                        'paid' => ['text' => 'Pagado', 'bg_class' => 'bg-green-100 text-green-800', 'border_color' => 'border-green-500'],
                        'shipped' => ['text' => 'Enviado', 'bg_class' => 'bg-indigo-100 text-indigo-800', 'border_color' => 'border-indigo-500'],
                        'delivered' => ['text' => 'Entregado', 'bg_class' => 'bg-pink-100 text-pink-800', 'border_color' => 'border-pink-500'],
                        'canceled' => ['text' => 'Cancelado', 'bg_class' => 'bg-gray-100 text-gray-800', 'border_color' => 'border-gray-500'],
                        'pending' => ['text' => 'Pendiente', 'bg_class' => 'bg-yellow-100 text-yellow-800', 'border_color' => 'border-yellow-500'],
                    ];
                    $status = $statusMap[$order->status] ?? $statusMap['pending'];
                @endphp

                {{-- Tarjeta de Pedido --}}
                <div class="bg-white p-6 rounded-lg shadow-lg mb-6 border-l-8 {{ $status['border_color'] }} transition duration-300">

                    <div class="flex justify-between items-start border-b pb-3 mb-3">
                        <div>
                            <p class="text-sm font-semibold text-gray-500">ORDEN #</p>
                            <p class="text-xl font-bold text-gray-900">{{ $order->id }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $status['bg_class'] }}">
                                {{ $status['text'] }}
                            </span>
                            <p class="text-sm font-medium text-gray-700 mt-1">Total: ${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>

                    {{-- ÍTEMS DEL PEDIDO --}}
                    <div class="space-y-3">
                        @foreach ($order->items as $item)
                            <div class="flex items-center text-sm justify-between">
                                <span class="text-gray-700">{{ $item->quantity }}x {{ $item->product_name }}</span>
                                <span class="font-medium text-gray-600">${{ number_format($item->price, 2) }} c/u</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- FECHAS Y ESTADO --}}
                    <div class="border-t pt-3 mt-4 text-sm">
                        <p class="font-medium text-gray-700">Fecha de Pedido: {{ $order->created_at->format('d/m/Y h:i A') }}</p>
                        @if($order->delivery_date)
                            <p class="font-medium text-gray-700">Fecha de Entrega: {{ $order->delivery_date->format('d/m/Y') }}</p>
                        @endif
                    </div>

                    {{-- BOTÓN DE ACCIÓN PARA EL CLIENTE --}}
                    @if ($order->status === 'shipped')
                        <div class="mt-4 pt-4 border-t border-dashed border-gray-300">
                            <p class="text-indigo-600 font-semibold mb-2">Su pedido está en tránsito. Por favor, confirme la recepción:</p>
                            <form action="{{ route('user.orders.markAsDelivered', $order) }}" method="POST" onsubmit="return confirm('¿Confirma que ha recibido su pedido? Esta acción no se puede deshacer.');">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 transition">
                                    Confirmar Entrega (Recibido)
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-6 text-center bg-white rounded-lg shadow-lg">
                    <p class="text-lg text-gray-600">Aún no has realizado ningún pedido.</p>
                    <a href="{{ route('welcome.index') }}" class="mt-4 inline-block font-semibold text-pink-600 hover:underline">
                        ¡Empieza a comprar ahora!
                    </a>
                </div>
            @endforelse

            {{-- Paginación --}}
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
