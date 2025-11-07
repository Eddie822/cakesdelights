<x-admin-layout>
    <div class="card">

     <x-validation-errors class="mb-4" />

        <form action="{{ route('admin.families.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>

                {{-- Campo de texto --}}
                <x-input
                    class="w-full"
                    placeholder="Ingrese el nombre de la familia"
                    name="name"
                    value="{{ old('name') }}"
                />
            </div>

            <div>
                <x-button type="submit">
                    Guardar
                </x-button>
            </div>
        </form>
    </div>
</x-admin-layout>
