<div>
    <form wire:submit.prevent="save">
        <div class="card">

            <x-validation-errors class="mb-4" />

            {{-- Campo de familia --}}
            <div class="mb-4">
                <x-label class="mb-2">Familia</x-label>

                <x-select class="w-full" wire:model.live="subcategory.family_id">
                    <option value="">Seleccione una familia</option>
                    @foreach ($families as $family)
                        <option value="{{ $family->id }}">{{ $family->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Campo de categoría --}}
            <div class="mb-4">
                <x-label class="mb-2">Categoría</x-label>

                <x-select class="w-full" wire:model="subcategory.category_id">
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-select>
            </div>

            {{-- Campo de nombre --}}
            <div class="mb-4">
                <x-label class="mb-2">Nombre</x-label>
                <x-input class="w-full" wire:model="subcategory.name"
                    placeholder="Ingrese el nombre de la subcategoría"
                    wire.model="subcategory.name"
                    />
            </div>

            <div>
                <x-button type="submit">Guardar</x-button>
            </div>
        </div>
    </form>
</div>
