<div>
    <form wire:submit.prevent="save">
        <div class="card">
            <x-validation-errors class="mb-4" />

            {{-- Familia --}}
            <div class="mb-4">
                <x-label class="mb-2">Familia</x-label>
                <x-select class="w-full" wire:model="subcategoryEdit.family_id">
                    <option value="">Seleccione una familia</option>
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}">{{ $family->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Categoría --}}
            <div class="mb-4">
                <x-label class="mb-2">Categoría</x-label>
                <x-select class="w-full" wire:model="subcategoryEdit.category_id">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Nombre --}}
            <div class="mb-4">
                <x-label class="mb-2">Nombre</x-label>
                <x-input class="w-full" wire:model="subcategoryEdit.name"
                    placeholder="Ingrese el nombre de la subcategoría" />
            </div>



            <div class="flex">
                <x-danger-button  class="mr-3" type="button" onclick="confirmDelete()">
                    Eliminar
                </x-danger-button>
                <div>
                    <x-button type="submit">Actualizar</x-button>
                </div>
            </div>
        </div>
    </form>

       <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="POST" id="delete-form">
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
</div>
