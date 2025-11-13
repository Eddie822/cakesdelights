<div class="mt-2">
    <section class="bg-white rounded-lg shadow overflow-hidden">
        <header class="bg-gray-900 px-4 py-2 rounded-t-lg border-b">
            <h2 class="text-lg font-medium text-white">
                Direcciones de Envío
            </h2>
        </header>

        <div class="p-4">
            @if ($newAddress)

                <x-validation-errors class="mb-4" />

                {{-- Fila 1: Tipo y Nombre --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                    <div class="col-span-1">
                        <x-select id="type" wire:model="createAddress.type"
                            class="w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-md shadow-sm">
                            <option value="">Tipo de dirección</option>
                            <option value="1">Domicilio (Envío)</option>
                            <option value="2">Oficina (Facturación)</option>
                        </x-select>
                        <x-input-error for="createAddress.type" class="mt-2" />
                    </div>

                    <div class="md:col-span-3">
                        <x-input id="calle" wire:model="createAddress.calle" type="text"
                            placeholder="Nombre de la dirección o Calle y número" class="w-full" />
                        <x-input-error for="createAddress.calle" class="mt-2" />
                    </div>
                </div>

                {{-- Fila 2: Ciudad, Estado, Código Postal, Referencias --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                    <div class="col-span-1">
                        <x-input id="ciudad" wire:model="createAddress.ciudad" type="text" placeholder="Ciudad" />
                        <x-input-error for="createAddress.ciudad" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                        <x-input id="estado" wire:model="createAddress.estado" type="text" placeholder="Estado" />
                        <x-input-error for="createAddress.estado" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                        <x-input id="codigo_postal" wire:model="createAddress.codigo_postal" type="text"
                            placeholder="Código Postal" />
                        <x-input-error for="createAddress.codigo_postal" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                        <x-input id="referencias" wire:model="createAddress.referencias" type="text"
                            placeholder="Referencias (Opcional)" />
                        <x-input-error for="createAddress.referencias" class="mt-2" />
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end items-center mt-4 pt-4 border-t border-gray-200">
                    <button type="button" wire:click="$set('newAddress', false)" class="btn btn-outline-gray">
                        Cancelar
                    </button>
                    <button wire:click="store" type="button"
                        class="btn btn-orange text-white font-semibold shadow-md ml-3">
                        Guardar Dirección
                    </button>
                </div>
            @else
                @if ($editAddress->id)
                    {{-- Formluario de edicion --}}
                    <x-validation-errors class="mb-4" />

                    {{-- Fila 1: Tipo y Nombre --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                        <div class="col-span-1">
                            <x-select id="type" wire:model="editAddress.type"
                                class="w-full border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-md shadow-sm">
                                <option value="">Tipo de dirección</option>
                                <option value="1">Domicilio (Envío)</option>
                                <option value="2">Oficina (Facturación)</option>
                            </x-select>
                            <x-input-error for="editAddress.type" class="mt-2" />
                        </div>

                        <div class="md:col-span-3">
                            <x-input id="calle" wire:model="editAddress.calle" type="text"
                                placeholder="Nombre de la dirección o Calle y número" class="w-full" />
                            <x-input-error for="editAddress.calle" class="mt-2" />
                        </div>
                    </div>

                    {{-- Fila 2: Ciudad, Estado, Código Postal, Referencias --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                        <div class="col-span-1">
                            <x-input id="ciudad" wire:model="editAddress.ciudad" type="text"
                                placeholder="Ciudad" />
                            <x-input-error for="editAddress.ciudad" class="mt-2" />
                        </div>

                        <div class="col-span-1">
                            <x-input id="estado" wire:model="editAddress.estado" type="text"
                                placeholder="Estado" />
                            <x-input-error for="editAddress.estado" class="mt-2" />
                        </div>

                        <div class="col-span-1">
                            <x-input id="codigo_postal" wire:model="editAddress.codigo_postal" type="text"
                                placeholder="Código Postal" />
                            <x-input-error for="editAddress.codigo_postal" class="mt-2" />
                        </div>

                        <div class="col-span-1">
                            <x-input id="referencias" wire:model="editAddress.referencias" type="text"
                                placeholder="Referencias (Opcional)" />
                            <x-input-error for="editAddress.referencias" class="mt-2" />
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end items-center mt-4 pt-4 border-t border-gray-200">
                        <button type="button" wire:click="$set('editAddress.id', null)" class="btn btn-outline-gray">
                            Cancelar
                        </button>
                        <button wire:click="update" type="button"
                            class="btn btn-orange text-white font-semibold shadow-md ml-3">
                            Actualizar Dirección
                        </button>
                    </div>
                @else
                    {{-- Listado de direcciones existentes --}}
                    @if ($addresses->count())
                        <ul class="grid grid-cols-3 gap-4">
                            @foreach ($addresses as $address)
                                <li class="{{ $address->default ? 'bg-orange-100' : 'bg-white' }} border rounded-lg shadow mb-2"
                                    wire:key="address-{{ $address->id }}">
                                    <div class="p-4 flex items-center">
                                        <div>
                                            <i class="fa-solid fa-house text-xl color-orange-400"></i>
                                        </div>
                                        <div class="flex-1 mx-4 text-xs">

                                            <p class="text-orange-400">
                                                {{ $address->type == 1 ? 'Domicilio' : 'Oficina' }}
                                            </p>

                                            <p class="font-semibold text-gray-700">
                                                {{ $address->calle }}
                                            </p>

                                            <p class="font-semibold text-gray-700">
                                                {{ $address->ciudad }}
                                            </p>

                                        </div>
                                        <div class="text-xs text-gray-800 flex flex-col">
                                            <button wire:click= "setDefaultAddress({{ $address->id }})"
                                                class="{{ $address->default ? 'text-orange-400' : '' }}">
                                                <i class="fa-solid fa-star"></i>
                                            </button>
                                            <button wire:click="edit({{ $address->id }})">
                                                <i class="fa-solid fa-pencil"></i>
                                            </button>
                                            <button wire:click="deleteAddress({{ $address->id }})">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600 text-center">No hay direcciones de envío registradas.</p>
                    @endif
                @endif

                <button wire:click="create" class="btn btn-outline-gray w-full flex items-center justify-center mt-4">
                    <i class="fas fa-plus mr-2"></i> Agregar Nueva Dirección
                </button>
            @endif
        </div>
    </section>
</div>
