<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2 flex justify-between items-center">
            Catálogo de Recetas
            <a href="{{ route('admin.recipes.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                <i class="fas fa-file-alt mr-2"></i> Crear Nueva Receta
            </a>
        </h1>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 rounded-lg shadow-md transition duration-300">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-600 text-red-800 rounded-lg shadow-md transition duration-300">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Comprobación de existencia --}}
        @if ($recipes->isEmpty())
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <span class="font-medium">Info:</span> Aun no tienes ninguna receta definida.
                <a href="{{ route('admin.recipes.create') }}" class="font-bold text-indigo-700 hover:underline ml-2">
                    Comienza creando una aquí.
                </a>
            </div>
        @else
            {{-- Contenedor de la Tabla --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

                    {{-- CABECERA (Desktop) --}}
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 hidden sm:table-header-group">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Nombre Receta</th>
                            <th scope="col" class="px-6 py-3">Producto Final</th>
                            <th scope="col" class="px-6 py-3 text-center">Rendimiento</th>
                            <th scope="col" class="px-6 py-3">Ingredientes (Resumen)</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach($recipes as $recipe)

                            {{-- VISTA MÓVIL (Tarjeta) --}}
                            <tr class="block sm:hidden border-b p-4 space-y-2 bg-white dark:bg-gray-800">
                                <td class="block text-base font-bold text-gray-900 border-none">
                                    <span class="text-xs text-pink-600 uppercase block">ID: {{ $recipe->id }}</span>
                                    {{ $recipe->name }}
                                </td>
                                <td class="block text-sm text-gray-600 border-none">
                                    <span class="font-semibold text-gray-500 block">Producto:</span>
                                    {{ $recipe->product->name ?? 'Producto Eliminado' }}
                                </td>
                                <td class="block text-sm text-gray-600 border-none">
                                    <span class="font-semibold text-gray-500 block">Rendimiento:</span>
                                    {{ $recipe->yield }} Unidades
                                </td>
                                <td class="block text-sm pt-4 border-none">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.recipes.edit', $recipe) }}"
                                           class="py-1 px-3 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('¿Eliminar esta receta?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="py-1 px-3 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- VISTA ESCRITORIO (Tabla) --}}
                            <tr class="hidden sm:table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $recipe->id }}</td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $recipe->name }}
                                </th>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ $recipe->product->name ?? 'Producto Eliminado' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300 text-center">
                                    {{ $recipe->yield }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    {{-- Muestra los primeros 3 ingredientes como resumen --}}
                                    <ul class="list-disc list-inside">
                                        @foreach($recipe->ingredients->take(3) as $detail)
                                            <li>{{ number_format($detail->quantity_required, 2) }} {{ $detail->material->unit ?? '' }} {{ $detail->material->name ?? '' }}</li>
                                        @endforeach
                                        @if($recipe->ingredients->count() > 3)
                                            <li class="list-none text-gray-400 italic ml-2">(+{{ $recipe->ingredients->count() - 3 }} más...)</li>
                                        @endif
                                    </ul>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.recipes.edit', $recipe) }}"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta receta?');">
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
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
