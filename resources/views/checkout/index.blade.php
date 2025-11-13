<x-app-layout>
    <div class="text-gray-700">
        <div class="grid grid-cols-2 lg:grid-cols-2">
            {{-- Columna izquierda: método de pago --}}
            <div class="col-span-1 p-4 bg-white">
                <div class="lg:max-w-[40rem] py-12 px-4 lg:pr-8 sm:pl-6 lg:pl-8 ml-auto">
                    <h1 class="text-2xl font-semibold mb-4">Pago</h1>

                    <div class="shadow rounded-lg overflow-hidden border border-gray-400">
                        <ul class="divide-y divide-gray-400">
                            <li>
                                <label class="p-4 flex items-center">
                                    <input type="radio" x-model="pago" value="1" checked>
                                    <span class="ml-3">Tarjeta de Crédito o Débito (Stripe)</span>
                                    <img class="h-6 ml-auto" src="https://codersfree.com/img/payments/credit-cards.png"
                                        alt="tarjetas">
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Columna derecha: resumen del carrito --}}
            <div class="col-span-1 bg-orange-50 p-4">
                <div class="lg:max-w-[40rem] py-12 px-4 lg:lr-8 sm:pr-6 lg:pr-8 mr-auto bg-white rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Resumen de compra</h2>

                    <ul>
                        @foreach (Cart::instance('shopping')->content() as $item)
                            <li class="flex items-center space-x-3 mb-4 border-b pb-2">
                                <img src="{{ $item->options->image }}" class="h-12 w-12 object-cover rounded"
                                    alt="{{ $item->name }}">
                                <div class="flex-1">
                                    <p class="font-medium">{{ $item->name }}</p>
                                    <p class="text-sm text-gray-600">${{ number_format($item->price, 2) }}</p>
                                </div>
                                <p>x{{ $item->qty }}</p>
                            </li>
                        @endforeach
                    </ul>

                    <div class="flex justify-between items-center mt-4 border-t pt-4">
                        <p class="text-lg font-semibold">Total</p>
                        <p class="text-lg font-semibold">
                            ${{ number_format(Cart::instance('shopping')->subtotal(0, '', ''), 2) }} MXN</p>
                    </div>

                    {{-- Formulario de pago --}}
                    <form action="{{ route('checkout.process') }}" method="POST" class="mt-6">
                        @csrf
                       

                        {{-- 💡 CAMBIO CLAVE: El campo hidden siempre se renderiza --}}
                        <input type="hidden" name="shipping_address_id" value="{{ $defaultAddress->id ?? '' }}">

                        @if (!$defaultAddress)
                            <p class="text-red-500 font-semibold">No tienes una dirección predeterminada. Añádela
                                primero.</p>
                        @endif

                        <button type="submit"
                            class="w-full bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700 transition"
                            @if (!$defaultAddress) disabled @endif>
                            Realizar pedido
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
