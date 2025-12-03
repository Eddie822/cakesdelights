<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2 flex justify-between items-center">
            Gestión de Proveedores
            <a href="{{ route('admin.suppliers.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                <i class="fas fa-plus mr-2"></i> Añadir Proveedor
            </a>
        </h1>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 rounded-lg shadow-md transition duration-300">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Contenedor de la Tabla (Estilo de Productos) --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

                {{-- CABECERA (Estilo de Productos) --}}
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 hidden sm:table-header-group">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th> {{-- AGREGADO: Columna ID --}}
                        <th scope="col" class="px-6 py-3">Nombre</th>
                        <th scope="col" class="px-6 py-3">Contacto</th>
                        <th scope="col" class="px-6 py-3">Teléfono / Email</th>
                        <th scope="col" class="px-6 py-3">RFC</th>
                        <th scope="col" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach($suppliers as $supplier)

                        {{-- VISTA DE TARJETA (VISIBLE SOLO EN MOBILE) --}}
                        <tr class="block sm:hidden border-b p-4 space-y-2 bg-white dark:bg-gray-800">
                            <td class="block text-base font-bold text-gray-900 border-none">
                                <span class="text-xs text-pink-600 uppercase block">ID: {{ $supplier->id }}</span> {{-- AGREGADO: ID en mobile --}}
                                <span class="text-xs text-gray-500 uppercase block">Nombre:</span>
                                {{ $supplier->name }}
                            </td>

                            <td class="block text-sm text-gray-600 border-none">
                                <span class="font-semibold text-gray-500 block">Contacto:</span>
                                {{ $supplier->contact_person ?? 'N/A' }}
                            </td>

                            <td class="block text-sm text-gray-600 border-none">
                                <span class="font-semibold text-gray-500 block">Teléfono:</span>
                                {{ $supplier->phone ?? 'N/A' }}
                                <span class="font-semibold text-gray-500 block mt-1">Email:</span>
                                {{ $supplier->email ?? 'N/A' }}
                                <span class="font-semibold text-gray-500 block mt-1">RFC:</span>
                                {{ $supplier->rfc ?? 'N/A' }}
                            </td>

                            {{-- Acciones Móviles --}}
                            <td class="block text-sm pt-4 border-none">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                       class="py-1 px-3 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-md">
                                        Editar
                                    </a>

                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar al proveedor {{ $supplier->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="py-1 px-3 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>


                        {{-- VISTA DE TABLA (Estilo de Productos) --}}
                        <tr class="hidden sm:table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700 ">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->id }}</td> {{-- AGREGADO: Celda ID --}}
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $supplier->name }}
                            </th>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $supplier->contact_person ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                <p>{{ $supplier->phone ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400">{{ $supplier->email ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $supplier->rfc ?? 'N/A' }}</td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                                    Editar
                                </a>
                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Deseas eliminar al proveedor {{ $supplier->name }}?');">
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
            {{ $suppliers->links() }}
        </div>
    </div>
</x-admin-layout>
