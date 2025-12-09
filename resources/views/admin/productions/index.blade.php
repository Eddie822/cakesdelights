<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2 flex justify-between items-center">
            Control de Lotes de Producción
            <a href="{{ route('admin.productions.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition shadow-md">
                <i class="fas fa-hammer mr-2"></i> Iniciar Producción
            </a>
        </h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID Lote</th>
                        <th scope="col" class="px-6 py-3">Receta / Producto</th>
                        <th scope="col" class="px-6 py-3 text-center">Cantidad</th>
                        <th scope="col" class="px-6 py-3 text-center">Estado</th>
                        <th scope="col" class="px-6 py-3">Iniciado Por</th>
                        <th scope="col" class="px-6 py-3">Fechas</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($productions as $production)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">#{{ $production->id }}</td>
                            <td class="px-6 py-4">
                                @if($production->details->isNotEmpty())
                                    <span class="font-bold text-indigo-600">{{ $production->details->first()->recipe->name }}</span>
                                    <br>
                                    <span class="text-xs text-gray-500">Producto: {{ $production->details->first()->recipe->product->name }}</span>
                                @else
                                    <span class="text-red-500">Error: Sin detalle</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-800">
                                {{ $production->details->first()->quantity_produced ?? 0 }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statuses = [
                                        'in_progress' => ['label' => 'En Progreso', 'class' => 'bg-yellow-100 text-yellow-800'],
                                        'completed' => ['label' => 'Completado', 'class' => 'bg-green-100 text-green-800'],
                                        'canceled' => ['label' => 'Cancelado', 'class' => 'bg-gray-100 text-gray-800'],
                                    ];
                                    $currentStatus = $statuses[$production->status] ?? ['label' => $production->status, 'class' => 'bg-gray-100'];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $currentStatus['class'] }}">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $production->user->name ?? 'Usuario Desconocido' }}</td>
                            <td class="px-6 py-4">
                                <p class="text-xs">Inicio: {{ $production->start_date->format('d/M/Y H:i') }}</p>
                                @if($production->end_date)
                                    <p class="text-xs">Fin: {{ $production->end_date->format('d/M/Y H:i') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex items-center space-x-2">
                                {{-- Botón VER --}}
                                <a href="{{ route('admin.productions.show', $production) }}"
                                   class="text-blue-600 hover:text-blue-900 font-medium text-sm border border-blue-200 rounded px-2 py-1 hover:bg-blue-50 transition">
                                    Ver
                                </a>

                                @if ($production->status === 'in_progress')
                                    <form action="{{ route('admin.productions.complete', $production) }}" method="POST" onsubmit="return confirm('¿Confirmar que la producción terminó? Esto descontará los ingredientes del almacén.');">
                                        @csrf
                                        <button type="submit" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded text-xs px-2 py-1 text-center inline-flex items-center transition">
                                            <i class="fas fa-check mr-1"></i> Completar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-green-500 font-semibold text-xs flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i> Listo
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No hay lotes de producción registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $productions->links() }}
        </div>
    </div>
</x-admin-layout>
