<x-admin-layout>

    <form action="{{ route('admin.categories.update', $category ) }}" method="POST">
        @csrf
        @method('PUT')
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
                            @selected(old('family_id', $category->family_id) == $family->id)>
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
                    value="{{ old('name', $category->name) }}" />
            </div>
            <div class="flex justify-line">
                <x-danger-button class="mr-4" onclick="confirmDelete()">
                    Eliminar
                </x-danger-button>

                <x-button type="submit">
                    Editar
                </x-button>
            </div>
        </div>
    </form>


    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" id="delete-form">
        @csrf
        @method('DELETE')
    </form>

     @push('js')
        <script>
            function confirmDelete() {
                Swal.fire({
                    title: "Estas seguro?",
                    text: "No podras revertir este cambio!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Eliminar!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            // title: "Eliminado!",
                            // text: "La familia ha sido borrado.",
                            // icon: "success"
                        });

                        document.getElementById('delete-form').submit();
                    }
                });
            }
        </script>
    @endpush
</x-admin-layout>
