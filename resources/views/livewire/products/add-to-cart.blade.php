<div class="card">
    <div class="grid md:grid-cols-2 gap-6">
        <div class="col-span-1">
            <figure class="mb-2">
                <img class="aspect-[16/9] object-cover w-full rounded-lg" src="{{ $product->image }}"
                    alt="{{ $product->name }}">
            </figure>

            <div class="text-sm">
                {{ $product->description }}
            </div>
        </div>
        <div class="col-span-1">
            <h1 class="text-xl font-semibold text-gray-700 mb-2">
                {{ $product->name }}
            </h1>

            <div class="flex items-center space-x-2 mb-4">
                <ul class="flex">
                    <li>
                        <i class="fa-solid fa-star text-yellow-300"></i>
                    </li>
                    <li>
                        <i class="fa-solid fa-star text-yellow-300"></i>
                    </li>
                    <li>
                        <i class="fa-solid fa-star text-yellow-300"></i>
                    </li>
                    <li>
                        <i class="fa-solid fa-star text-yellow-300"></i>
                    </li>
                    <li>
                        <i class="fa-solid fa-star text-yellow-300"></i>
                    </li>
                </ul>

                <p class="text-gray-700 text-sm">4.9 (40)</p>
            </div>

            <div class="text-2xl text-gray-700 mb-4">
                $ {{ number_format($product->price, 2) }}
            </div>

            <div class="text-2xl text-gray-700 mb-4">
                Disponible: {{ ($product->stock ) }}
            </div>

            <div class="flex items-center space-x-6"
            x-data="{
            qty: @entangle('qty'),
            }" >
                <button class="btn btn-gray"
                x-on:click="qty = qty -1"
                x-bind:disabled="qty ==1">
                    -
                </button>
                <span class="inline-block w-2 text-center" x-text="qty"></span>
                <button class="btn btn-gray"
                x-on:click="qty++">
                    +
                </button>
            </div>

            <div class="mt-6">
                <button class="btn btn-orange px-8"
                wire:click="add_to_cart"
                wire:loading.attr="disabled">
                    Agregar al carrito
                </button>
            </div>

            <div class="flex items-center mt-6">
                <i class="fa-solid fa-truck-fast text-2xl"></i>
                <span class="text-gray-700 ml-2">Entrega en 1-2 dias</span>
            </div>
        </div>
    </div>

</div>
