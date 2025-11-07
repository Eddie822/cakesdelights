<x-admin-layout>
    <div class="card">
        <form action="{{ route('admin.families.update', $family) }}" method="POST">
            @csrf

            @method('PUT')
            <div class="mb-4">
                <x-label class="mb-2">
                    Nombre
                </x-label>

                {{-- Campo de texto --}}
                <x-input class="w-full" placeholder="Ingrese el nombre de la familia" name="name"
                    value="{{ old('name', $family->name) }}" />
            </div>

            <div class="flex justify-right ">

                <x-danger-button class="mr-4" onclick="confirmDelete()">
                    Eliminar
                </x-danger-button>

                <x-button class="ml-3" type="submit">
                    Editar
                </x-button>
            </div>
        </form>
    </div>

    <form action="{{ route('admin.families.destroy', $family) }}" method="POST" id="delete-form">
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
