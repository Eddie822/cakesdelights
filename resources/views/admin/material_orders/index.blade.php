<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2 flex justify-between items-center">
            Pedidos de Materia Prima
            <a href="{{ route('admin.material_orders.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                <i class="fas fa-file-invoice mr-2"></i> Nuevo Pedido
            </a>
        </h1>

        {{-- Mensajes de éxito y estado --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 rounded-lg shadow-md transition duration-300">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('info'))
            <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-600 text-blue-800 rounded-lg shadow-md transition duration-300">
                <p>{{ session('info') }}</p>
            </div>
        @endif

        {{-- Comprobación de existencia de órdenes --}}
        @if ($orders->isEmpty())
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <span class="font-medium">Info:</span> Aun no hay pedidos de materia prima registrados.
            </div>
        @else
            {{-- Contenedor de la Tabla (Estilo Flowbite/Admin) --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

                    {{-- CABECERA (Desktop/Tablet) --}}
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 hidden sm:table-header-group">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Proveedor</th>
                            <th scope="col" class="px-6 py-3">Costo Total</th>
                            <th scope="col" class="px-6 py-3">Fecha Pedido</th>
                            <th scope="col" class="px-6 py-3">Entrega Esperada</th>
                            <th scope="col" class="px-6 py-3 text-center">Estado</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)

                            @php
                                $statusMap = [
                                    'pending' => ['text' => 'Pendiente', 'class' => 'bg-blue-100 text-blue-800'],
                                    'ordered' => ['text' => 'Ordenado', 'class' => 'bg-yellow-100 text-yellow-800'],
                                    'received' => ['text' => 'Recibido', 'class' => 'bg-green-100 text-green-800'],
                                    'canceled' => ['text' => 'Cancelado', 'class' => 'bg-gray-100 text-gray-800'],
                                ];
                                $status = $statusMap[$order->status] ?? $statusMap['pending'];
                            @endphp

                            {{-- VISTA DE TARJETA (VISIBLE SOLO EN MOBILE) --}}
                            <tr class="block sm:hidden border-b p-4 space-y-2 bg-white dark:bg-gray-800">
                                <td class="block text-base font-bold text-gray-900 border-none">
                                    <span class="text-xs text-pink-600 uppercase block">Pedido #{{ $order->id }}</span>
                                    {{ $order->supplier->name ?? 'Proveedor Eliminado' }}
                                </td>

                                <td class="block text-sm text-gray-600 border-none">
                                    <span class="font-semibold text-gray-500 block">Total:</span>
                                    <span class="font-bold text-indigo-700">${{ number_format($order->total_cost, 2) }}</span>
                                </td>

                                <td class="block text-sm text-gray-600 border-none">
                                    <span class="font-semibold text-gray-500 block">Fecha Pedido:</span>
                                    {{ $order->created_at->format('d/m/Y') }}
                                    <span class="font-semibold text-gray-500 block mt-1">Entrega Esperada:</span>
                                    {{ $order->expected_delivery_date?->format('d/m/Y') ?? 'N/A' }}
                                </td>

                                <td class="block text-sm pt-4 border-none">
                                    <span class="font-semibold text-gray-500 block mb-2">Estado:</span>
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $status['class'] }}">
                                        {{ $status['text'] }}
                                    </span>

                                    {{-- Botón de RECEPCIÓN MANUAL (Mobile) --}}
                                    @if ($order->status === 'ordered')
                                        <form action="{{ route('admin.material_orders.receive', $order) }}" method="POST" class="mt-2" onsubmit="return confirm('¿Confirmas que recibiste el material? Esto actualizará tu stock.');">
                                            @csrf
                                            <button type="submit" class="w-full py-1 text-xs bg-green-500 text-white rounded-lg hover:bg-green-600 transition shadow-md">
                                                Confirmar Recepción
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>


                            {{-- VISTA DE TABLA (VISIBLE SOLO EN DESKTOP/TABLET) --}}
                            <tr class="hidden sm:table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $order->id }}</td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $order->supplier->name ?? 'Proveedor Eliminado' }}
                                </th>
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-indigo-700 dark:text-indigo-400">
                                    ${{ number_format($order->total_cost, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $order->expected_delivery_date?->format('d/m/Y') ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $status['class'] }}">
                                        {{ $status['text'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    {{-- Botón de RECEPCIÓN MANUAL (Desktop) --}}
                                    @if ($order->status === 'ordered')
                                        <form action="{{ route('admin.material_orders.receive', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Confirmas la recepción? Esto sumará los ítems al almacén.');">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition shadow-sm">
                                                Recibido
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Enlace para ver detalles (Asumiendo que hay una ruta show) --}}
                                    <a href="{{ route('admin.material_orders.show', $order) }}"
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-6 p-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
