<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-3">Editar Proveedor: {{ $supplier->name }}</h1>

            <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nombre de la Empresa --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Nombre de la Empresa</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Persona de Contacto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="contact_person">Persona de Contacto</label>
                    <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('contact_person') border-red-500 @enderror" required>
                    @error('contact_person') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Teléfono --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">Teléfono</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('phone') border-red-500 @enderror" required>
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}"
                            class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror" required>
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- RFC --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="rfc">RFC</label>
                    <input type="text" name="rfc" id="rfc" value="{{ old('rfc', $supplier->rfc) }}"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('rfc') border-red-500 @enderror" required>
                    @error('rfc') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Dirección --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="address">Dirección</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full border-gray-300 px-4 py-2 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('address') border-red-500 @enderror" required>{{ old('address', $supplier->address) }}</textarea>
                    @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Botones de Acción --}}
                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.suppliers.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                        Actualizar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
