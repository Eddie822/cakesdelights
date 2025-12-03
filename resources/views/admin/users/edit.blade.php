<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">

        <div class="bg-white shadow-xl rounded-lg p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-3">Editar Usuario: {{ $user->name }}</h1>

            {{-- Manejo de Errores --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-600 text-red-800 rounded-lg shadow-md">
                    <p class="font-semibold mb-2">Por favor, corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Campo Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Campo Apellido --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="last_name">Apellido</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('last_name') border-red-500 @enderror">
                    @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Campo Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Campo Teléfono --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">Teléfono</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror">
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- CAMPO AGREGADO: Documento / RFC / INE --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="document_number">Número de Documento (INE/RFC)</label>
                    <input type="text" name="document_number" id="document_number" value="{{ old('document_number', $user->document_number) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('document_number') border-red-500 @enderror">
                    @error('document_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Botones de Acción --}}
                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
