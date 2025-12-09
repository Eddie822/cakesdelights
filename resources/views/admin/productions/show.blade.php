<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6 sm:p-8">

            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3 flex justify-between items-center">
                Detalle del Lote #{{ $production->id }}

                <a href="{{ route('admin.productions.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </h1>

            {{-- 1. INFORMACIÓN GENERAL --}}
            @php
                $statusMap = [
                    'pending' => ['text' => 'Pendiente', 'class' => 'bg-blue-100 text-blue-800'],
                    'in_progress' => ['text' => 'En Progreso', 'class' => 'bg-yellow-100 text-yellow-800'],
                    'completed' => ['text' => 'Completado', 'class' => 'bg-green-100 text-green-800'],
                    'canceled' => ['text' => 'Cancelado', 'class' => 'bg-gray-100 text-gray-800'],
                ];
                $status = $statusMap[$production->status] ?? $statusMap['pending'];
                $detail = $production->details->first();
                $recipe = $detail->recipe;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 p-4 bg-gray-50 rounded-lg border">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Receta Utilizada</p>
                    <p class="text-lg font-medium text-gray-900">{{ $recipe->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">Producto Final: <strong>{{ $recipe->product->name ?? 'N/A' }}</strong></p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Cantidad Producida</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $detail->quantity_produced ?? 0 }} Unidades</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Estado y Fechas</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status['class'] }} mb-2">
                        {{ $status['text'] }}
                    </span>
                    <p class="text-xs text-gray-500">Inicio: {{ $production->start_date->format('d/m/Y H:i') }}</p>
                    @if($production->end_date)
                        <p class="text-xs text-gray-500">Fin: {{ $production->end_date->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>

            {{-- 2. CONSUMO DE INGREDIENTES --}}
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                Consumo de Materia Prima
                <span class="text-sm font-normal text-gray-500 ml-2">(Calculado para este lote)</span>
            </h2>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs text-gray-700 uppercase">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">Ingrediente</th>
                            <th scope="col" class="px-6 py-3 text-center">Unidad</th>
                            <th scope="col" class="px-6 py-3 text-right">Por Unidad (Receta)</th>
                            <th scope="col" class="px-6 py-3 text-right">Total Consumido</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            // Factor de escala: (Cantidad Producida / Rendimiento de Receta)
                            $scaleFactor = ($detail->quantity_produced ?? 0) / ($recipe->yield ?? 1);
                        @endphp

                        @foreach($recipe->ingredients as $ingredient)
                            @php
                                $totalConsumed = $ingredient->quantity_required * $scaleFactor;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ $ingredient->material->name ?? 'Material Eliminado' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-600">
                                    {{ $ingredient->material->unit ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-500">
                                    {{ number_format($ingredient->quantity_required, 3) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-red-600">
                                    - {{ number_format($totalConsumed, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 3. ACCIONES --}}
            @if ($production->status === 'in_progress')
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                    <form action="{{ route('admin.productions.complete', $production) }}" method="POST" onsubmit="return confirm('¿Confirmar finalización? Se descontará el inventario mostrado arriba.');">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition shadow-lg">
                            <i class="fas fa-check-circle mr-2"></i> Completar Producción y Actualizar Stock
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-admin-layout>
