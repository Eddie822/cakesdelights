<x-app-layout>
    <div class="text-gray-700" x-data="{ pago: 1 }">

        <!-- 🔸 Sección de Direcciones -->
        <div class="p-6 bg-white mb-6 rounded-lg shadow border border-gray-300">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Direcciones de Envío</h2>

            {{-- Aquí se inyecta el componente Livewire de direcciones --}}
            @livewire('shipping-addresses')
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-2">
            <!-- Sección izquierda (métodos de pago) -->
            <div class="col-span-1 p-4 bg-white">
                <div class="lg:max-w-[40rem] py-12 px-4 lg:pr-8 sm:pl-6 lg:pl-8 ml-auto">
                    <h1 class="text-2xl font-semibold mb-2">
                        Pago
                    </h1>

                    <div class="shadow rounded-lg overflow-hidden border border-gray-400">
                        <ul class="divide-y divide-gray-400">
                            <li>
                                <label class="p-4 flex items-center">
                                    <input type="radio" x-model="pago" value="1">
                                    <span class="ml-3">
                                        Tarjeta de Crédito o Débito
                                    </span>
                                    <img class="h-6 ml-auto" src="https://codersfree.com/img/payments/credit-cards.png" alt="">
                                </label>

                                <div class="p-4 bg-gray-100 text-center">
                                    <i class="fa-regular fa-credit-card text-9xl"></i>

                                    <p class="mt-4 text-gray-600">
                                        Luego de hacer click en "Realizar pedido", serás redirigido a la pasarela segura de <strong>Stripe</strong> para completar tu compra.
                                    </p>

                                    <form action="{{ route('checkout.process') }}" method="POST" class="mt-6">
                                        @csrf
                                        <div class="flex flex-col items-center space-y-3">
                                            <div class="text-lg font-semibold text-gray-800">
                                                Total a pagar:
                                                <span class="text-orange-600 text-xl font-bold">
                                                    ${{ Cart::instance('shopping')->subtotal() }}
                                                </span>
                                            </div>

                                            <button type="submit"
                                                class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition">
                                                Realizar pedido
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sección derecha (resumen del pedido) -->
            <div class="col-span-1 bg-orange-50 p-4">
                <div class="lg:max-w-[40rem] py-12 px-4 lg:lr-8 sm:pr-6 lg:pr-8 mr-auto">
                    <h2 class="text-xl font-semibold mb-4">Resumen del Pedido</h2>
                    <ul class="divide-y divide-gray-300">
                        @foreach (Cart::instance('shopping')->content() as $item)
                            <li class="py-3 flex items-center space-x-3">
                                <img src="{{ $item->options->image }}" alt="{{ $item->name }}" class="h-12 w-12 rounded-md object-cover">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-600">Cantidad: {{ $item->qty }}</p>
                                </div>
                                <p class="text-gray-700 font-semibold">${{ $item->price }}</p>
                            </li>
                        @endforeach
                    </ul>

                    <hr class="my-4 border-gray-300">

                    <div class="space-y-2 text-gray-700">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>${{ Cart::instance('shopping')->subtotal() }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span>${{ Cart::instance('shopping')->subtotal() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
