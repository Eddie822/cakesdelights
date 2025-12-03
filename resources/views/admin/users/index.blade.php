<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Gestión de Usuarios</h1>

        {{-- Mensajes de éxito --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 rounded-lg shadow-md transition duration-300">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Contenedor de la Tabla --}}
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">

                    {{-- CABECERA (Visible solo en Desktop/Tablet) --}}
                    <thead class="bg-gray-50 hidden sm:table-header-group">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Creación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actualización</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)

                            {{-- VISTA DE TARJETA (VISIBLE SOLO EN MOBILE) --}}
                            <tr class="block sm:hidden border-b last:border-b-0 p-4 space-y-2">

                                {{-- Nombre y ID --}}
                                <td class="block text-sm font-medium text-gray-900 border-none">
                                    <span class="font-bold text-pink-600">#{{ $user->id }}</span> - {{ $user->name }} {{ $user->last_name }}
                                </td>

                                {{-- Email --}}
                                <td class="block text-sm text-gray-600 border-none">
                                    <span class="font-semibold text-gray-500 block">Email:</span>
                                    {{ $user->email }}
                                </td>

                                {{-- Teléfono y Documento --}}
                                <td class="block text-sm text-gray-600 border-none">
                                    <span class="font-semibold text-gray-500 block">Teléfono:</span>
                                    {{ $user->phone ?? 'N/A' }}
                                    <span class="font-semibold text-gray-500 block mt-1">Documento:</span>
                                    {{ $user->document_number ?? 'N/A' }}
                                </td>

                                {{-- Acciones Móviles --}}
                                <td class="block text-sm pt-4 border-none">
                                    <div class="flex flex-col space-y-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="w-full text-center py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-md">
                                            Editar
                                        </a>

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar a {{ $user->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-md">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>


                            {{-- VISTA DE TABLA (VISIBLE SOLO EN DESKTOP/TABLET) --}}
                            <tr class="hidden sm:table-row hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">{{ $user->name }} {{ $user->last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->document_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Deseas eliminar a {{ $user->name }}?');">
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
        </div>

        {{-- Paginación --}}
        <div class="mt-6 p-4 bg-white rounded-lg shadow-md">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
