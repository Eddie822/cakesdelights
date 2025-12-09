<div>
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
        <div class="lg:col-span-5">
            <div class="flex justify-between mb-2">
                <h1 class="font-bold text-lg mb-4">Carrito de Compras ({{ Cart::count() }} productos)</h1>
                <button class="font-semibold text-gray-600 underline hover:text-orange-400 hover:no-underline"
                    wire:click="destroy">
                    Limpiar carrito
                </button>
            </div>

            <div class="card">
                <ul class="space-y-4">

                    @forelse (Cart::content() as $item)
                        <li class="lg:flex items-center">
                            <img class="w-full lg:w-36 aspect-square object-cover object-center mr-2"
                                src="{{ $item->options->image }}" alt="{{ $item->name }}">

                            <div class="w-80">
                                <p class="text-sm">
                                    <a href="{{ route('products.show', $item->id) }}">
                                        {{ $item->name }}
                                    </a>
                                </p>

                                <button
                                    class="bg-red-100 hover:bg-red-200 text-red-800 text-xs font-semibold rounded px-2.5 py-0.5"
                                    wire:click="remove('{{ $item->rowId }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="remove('{{ $item->rowId }}')">
                                    <i class="fas fa-trash-alt"></i>
                                    Remover
                                </button>
                            </div>
                            <p class="font-semibold">
                                ${{ $item->price }}
                            </p>

                            <div class="ml-auto space-x-3">
                                {{-- Botón Disminuir: Deshabilitado si la cantidad es 1 o menos --}}
                                <button class="btn btn-gray"
                                    wire:click="decrease('{{ $item->rowId }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="decrease('{{ $item->rowId }}')"
                                    @if($item->qty <= 1) disabled @endif>
                                    -
                                </button>

                                <span class="inline-block w-2 text-center"> {{ $item->qty }}</span>

                                {{-- Botón Aumentar: Se deshabilita si existe el modelo Y se alcanzó el stock --}}
                                <button class="btn btn-gray"
                                    wire:click="increase('{{ $item->rowId }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="increase('{{ $item->rowId }}')"
                                    @if($item->model && $item->qty >= $item->model->stock) disabled @endif>
                                    +
                                </button>
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-gray-60                            <p class="text-sm">
                                    No hay productos en el carrito
                                </li>
                    @endforelse

                </ul>
            </div>
        </div>
        <div class="lg:col-span-2">

            <div class="card">
                <div class="flex justify-between font-semibold">
                    <p>
                        Total:
                    </p>
                    <p>
                        ${{ Cart::subtotal() }}
                    </p>
                </div>

                <a href="{{ route('shipping.index') }}">
                    <button class="btn btn-orange w-full mt-4">
                        Continuar con la compra
                    </button>
                </a>
            </div>

        </div>
    </div>
</div>
