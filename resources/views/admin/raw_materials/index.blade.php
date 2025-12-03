<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2 flex justify-between items-center">
            Almacén de Materia Prima
            <a href="{{ route('admin.raw_materials.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                <i class="fas fa-plus mr-2"></i> Añadir Materia Prima
            </a>
        </h1>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 rounded-lg shadow-md transition duration-300">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Comprobación de existencia de materiales --}}
        @if ($materials->isEmpty())
            {{-- Mensaje si NO HAY NINGÚN registro de materia prima --}}
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <span class="font-medium">Info:</span> Aun no hay registros de materia prima en el almacén.
                <a href="{{ route('admin.raw_materials.create') }}" class="font-bold text-indigo-700 hover:underline ml-2">
                    Comienza agregando materiales aquí.
                </a>
            </div>
        @else

            {{-- Lógica para verificar si HAY ALERTA DE BAJO STOCK en la colección --}}
            @php
                $hasLowStock = $materials->contains(function ($material) {
                    return $material->current_stock <= $material->min_stock;
                });
            @endphp

            @if ($hasLowStock)
                {{-- ALERTA GLOBAL DE BAJO STOCK (Realizar Pedido) --}}
                <div class="mb-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow-md transition duration-300 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
                    <div>
                        <p class="font-bold text-lg">⚠️ ¡ALERTA DE INVENTARIO BAJO!</p>
                        <p class="text-sm">Una o más materias primas necesitan ser repuestas. ¡Realiza un pedido ahora!</p>
                    </div>
                    <a href="{{ route('admin.material_orders.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition shadow-md whitespace-nowrap">
                        Realizar Pedido
                    </a>
                </div>
            @endif

            {{-- Contenedor de la Tabla (Estilo Flowbite/Admin) --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

                    {{-- CABECERA (Desktop/Tablet) --}}
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 hidden sm:table-header-group">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Material</th>
                            <th scope="col" class="px-6 py-3">Proveedor</th>
                            <th scope="col" class="px-6 py-3 text-center">Stock (Unidad)</th>
                            <th scope="col" class="px-6 py-3 text-center">Mínimo</th>
                            <th scope="col" class="px-6 py-3 text-center">Estado</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach($materials as $material)

                            @php
                                $isLowStock = $material->current_stock <= $material->min_stock;
                                $statusClass = $isLowStock ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
                                $statusText = $isLowStock ? '¡Bajo!' : 'Suficiente';
                            @endphp

                            {{-- VISTA DE TARJETA (VISIBLE SOLO EN MOBILE) --}}
                            <tr class="block sm:hidden border-b p-4 space-y-2 bg-white dark:bg-gray-800">
                                <td class="block text-base font-bold text-gray-900 border-none">
                                    <span class="text-xs text-pink-600 uppercase block">ID: {{ $material->id }}</span>
                                    {{ $material->name }} ({{ $material->unit }})
                                </td>

                                <td class="block text-sm text-gray-600 border-none">
                                    <div class="flex justify-between items-center mb-2">
                                        <div>
                                            <span class="font-semibold text-gray-500 block">Stock Actual / Mínimo:</span>
                                            {{ number_format($material->current_stock, 2) }} / {{ number_format($material->min_stock, 2) }}
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </td>

                                <td class="block text-sm text-gray-600 border-none">
                                    <span class="font-semibold text-gray-500 block">Proveedor:</span>
                                    {{ $material->supplier->name ?? 'Sin Asignar' }}
                                </td>

                                {{-- Acciones Móviles --}}
                                <td class="block text-sm pt-4 border-none">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.raw_materials.edit', $material->id) }}"
                                           class="py-1 px-3 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-md">
                                            Editar
                                        </a>

                                        <form action="{{ route('admin.raw_materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar {{ $material->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="py-1 px-3 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>


                            {{-- VISTA DE TABLA (VISIBLE SOLO EN DESKTOP/TABLET) --}}
                            <tr class="hidden sm:table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $material->id }}</td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $material->name }}
                                </th>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $material->supplier->name ?? 'N/A' }}</td>

                                {{-- Stock Actual (Unidad) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300 text-center">{{ number_format($material->current_stock, 2) }} {{ $material->unit }}</td>

                                {{-- Stock Mínimo --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 text-center">{{ number_format($material->min_stock, 2) }} {{ $material->unit }}</td>

                                {{-- Estado --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.raw_materials.edit', $material->id) }}"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.raw_materials.destroy', $material->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Deseas eliminar {{ $material->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition shadow-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-6 p-4">
                {{ $materials->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
