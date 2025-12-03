<x-app-layout>
    <div class="min-h-screen bg-[#f3e7d9] py-10">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-10 text-center border-b-4 border-yellow-400 pb-2">
                Nuestra Familia: {{ $family->name }}
            </h1>

            @if ($products->isEmpty())
                <div class="text-center p-10 bg-white rounded-lg shadow-lg">
                    <p class="text-xl text-gray-600">No hemos encontrado productos en esta familia por el momento.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($products as $product)
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 duration-300">

                            {{-- Imagen del Producto --}}
                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                class="w-full h-52 object-cover object-center">

                            <div class="p-5">

                                {{-- Nombre y Precio --}}
                                <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h2>
                                <p class="text-2xl font-extrabold text-pink-600 mb-3">${{ number_format($product->price, 2) }}</p>

                                {{-- Indicador de Stock --}}
                                <div class="mb-4 text-sm font-semibold">
                                    @if ($product->stock > 0)
                                        <p class="text-green-600 flex items-center">
                                            <i class="fas fa-check-circle mr-2"></i> Disponible: {{ $product->stock }} unidades
                                        </p>
                                    @else
                                        <p class="text-red-600 flex items-center">
                                            <i class="fas fa-times-circle mr-2"></i> ¡Agotado! (Sin stock)
                                        </p>
                                    @endif
                                </div>

                                {{-- Botón de Acción --}}
                                @if ($product->stock > 0)
                                    {{-- Si hay stock, se muestra el botón de compra --}}
                                    <a href="{{ route('products.show', $product) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-500 hover:bg-red-600 transition shadow-md">
                                        <i class="fas fa-shopping-cart mr-2"></i> Agregar al Carrito
                                    </a>
                                @else
                                    {{-- Si no hay stock, se muestra el botón de Notificación --}}
                                    <button disabled
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-500 bg-gray-200 cursor-not-allowed shadow-inner">
                                        <i class="fas fa-delete mr-2"></i> Agregar
                                    </button>
                                @endif

                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
