@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div>
    <form wire:submit="store">
        <figure
            class="relative mb-6 group overflow-hidden rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">

            {{-- Botón flotante para cambiar la imagen --}}
            <div class="absolute top-4 right-4 z-10">
                <label
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full bg-white/80 dark:bg-gray-900/80 text-gray-700 dark:text-gray-300 shadow-md cursor-pointer backdrop-blur-sm transition-all hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600">
                    <i class="fas fa-camera"></i>
                    <span class="hidden sm:inline">Cambiar imagen</span>
                    <input type="file" accept="image/*" class="hidden" wire:model="image">
                </label>
            </div>

            {{-- Imagen del producto --}}
            <img class="aspect-[16/9] w-full object-cover object-center transition-transform duration-500 ease-in-out group-hover:scale-105"
                src="{{ $image ? $image->temporaryUrl() : Storage::url($productEdit['image_path']) }}"
                alt="Imagen del producto">

            {{-- Sombra y overlay al pasar el mouse --}}
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out rounded-2xl">
            </div>

        </figure>

        <x-validation-errors class="mb-4" />
        <div class="card">


            <div class="mb-4">
                <x-label>
                    SKU
                </x-label>

                <x-input wire:model="productEdit.sku" type="text" class="w-full mt-1" placeholder="SKU del producto">
                    SKU

                </x-input>

                <div class="mb-4">
                    <x-label>
                        Nombre
                    </x-label>

                    <x-input wire:model="productEdit.name" type="text" class="w-full mt-1"
                        placeholder="Nombre del producto" />
                </div>

                <div class="mb-4">
                    <x-label>
                        Descripción
                    </x-label>

                    <x-textarea wire:model="productEdit.description" class="w-full mt-1"
                        placeholder="Descripción del producto" />
                </div>

                <div class="mb-4">
                    <X-label class="mb-1">
                        Familias
                    </X-label>

                    <x-select wire:model.live="family_id" class="w-full">
                        <option value="" disabled>Seleccione una familia</option>
                        @foreach ($families as $family)
                            <option value="{{ $family->id }}">
                                {{ $family->name }}
                            </option>
                        @endforeach
                    </x-select>


                    {{-- Input de categorias --}}
                    <div class="mb-4 mt-4">
                        <X-label class="mb-1">
                            Categorias
                        </X-label>
                        <x-select wire:model.live="category_id" class="w-full">
                            <option value="" disabled>Seleccione una categoria</option>
                            @foreach ($this->categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    {{-- Input de subcategorias --}}
                    <div class="mb-4 mt-4">
                        <X-label class="mb-1">
                            Subcategorias
                        </X-label>
                        <x-select wire:model.live="productEdit.subcategory_id" class="w-full">
                            <option value="" disabled>Seleccione una subcategoria</option>
                            @foreach ($this->subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}">
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div class="mb-4">
                        <x-label value="Precio" class="text-lg font-bold mt-4" />

                        <x-input wire:model="productEdit.price" type="number" step="0.01" class="w-full mt-1"
                            placeholder="Precio del producto" />
                    </div>

                    <div class="flex justify-right ">

                        <x-danger-button class="mr-4" onclick="confirmDelete()">
                            Eliminar
                        </x-danger-button>

                        <x-button class="ml-3" type="submit">
                            Editar
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" id="delete-form">
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
