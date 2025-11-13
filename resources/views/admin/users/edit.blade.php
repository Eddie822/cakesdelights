<x-admin-layout>
    <div class="p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-semibold mb-6">Editar Usuario</h1>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium mb-1" for="name">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block font-medium mb-1" for="last_name">Apellido</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block font-medium mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label class="block font-medium mb-1" for="phone">Teléfono</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div class="flex space-x-2">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Guardar Cambios
                </button>

                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
