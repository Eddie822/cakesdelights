<x-app-layout>
    <div class="max-w-2xl mx-auto text-center py-12">
        <h1 class="text-3xl font-semibold text-green-600 mb-6">
            ¡Pago completado con éxito!
        </h1>
        <p class="mb-6 text-gray-700">
            Gracias por tu compra. Aquí está el resumen de tu pedido:
        </p>

        <ul class="text-left mx-auto max-w-md mb-6">
            @foreach ($order->items as $item)
                <li class="flex justify-between border-b py-2">
                    <span>{{ $item->product_name }} x{{ $item->quantity }}</span>
                    <span>${{ number_format($item->price, 2) }}</span>
                </li>
            @endforeach
        </ul>

        <p class="text-xl font-semibold">
            Total: ${{ number_format($order->total, 2) }} MXN
        </p>

        <a href="{{ route('welcome.index') }}"
            class="mt-6 inline-block bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition">
            Volver al inicio
        </a>
    </div>
</x-app-layout>
