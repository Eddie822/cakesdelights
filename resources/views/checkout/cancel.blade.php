<x-app-layout>
    <div class="p-10 text-center">
        <h1 class="text-2xl font-semibold text-red-600">Pago cancelado ❌</h1>
        <p class="mt-4 text-gray-700">No se realizó ningún cargo. Puedes intentar nuevamente.</p>
        <a href="{{ route('checkout.index') }}" class="mt-6 inline-block bg-orange-500 text-white px-6 py-2 rounded-md">
            Volver al pago
        </a>
    </div>
</x-app-layout>
