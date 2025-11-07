<x-admin-layout>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="card">

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label class="mb-2">
                    Familia
                </x-label>

                {{-- Select --}}
                <x-select  name="family_id" class="w-full">
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}"
                        @selected(old('family_id') == $family->id)>
>
                            {{ $family->name }}
                        </option>
                    @endforeach
                    </x-select>
            </div>

            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>

                {{-- Campo de texto --}}
                <x-input class="w-full" placeholder="Ingrese el nombre de la categoria" name="name"
                    value="{{ old('name') }}" />
            </div>
            <div>
                <x-button type="submit">
                    Guardar
                </x-button>
            </div>
        </div>
    </form>
</x-admin-layout>
