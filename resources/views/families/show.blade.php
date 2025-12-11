<x-app-layout>
    <div class="min-h-screen bg-[#f3e7d9] py-10">
        <div class="container mx-auto px-6">
            <h1 class="text-4xl font-extrabold text-gray-800 mb-6 text-center border-b-4 border-yellow-400 pb-2">
                Nuestra Familia: {{ $family->name }}
            </h1>

            @if ($products->isNotEmpty())
                {{-- SECCIÓN DE FILTROS Y ORDENAMIENTO --}}
                <div class="flex justify-end mb-8">
                    <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 sr-only">Ordenar por</label>
                        {{-- El evento onchange redirige a la URL con el nuevo parámetro 'sort' --}}
                        <select id="sort_by" name="sort_by" onchange="window.location.href = this.value;"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">

                            {{-- Ordenamiento por Relevancia (Default) --}}
                            <option value="{{ route('families.show', [$family, 'sort' => '']) }}"
                                {{ !request('sort') ? 'selected' : '' }}>Ordenar por Relevancia</option>

                            {{-- Precio --}}
                            <option value="{{ route('families.show', [$family, 'sort' => 'price_asc']) }}"
                                {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio (Menor a Mayor)</option>

                            <option value="{{ route('families.show', [$family, 'sort' => 'price_desc']) }}"
                                {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio (Mayor a Menor)</option>

                            {{-- Stock --}}
                            <option value="{{ route('families.show', [$family, 'sort' => 'stock_desc']) }}"
                                {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stock (Mayor a Menor)</option>

                            {{-- Nombre (Alfabético) --}}
                            <option value="{{ route('families.show', [$family, 'sort' => 'name_asc']) }}"
                                {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre (A - Z)</option>

                            <option value="{{ route('families.show', [$family, 'sort' => 'name_desc']) }}"
                                {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre (Z - A)</option>

                        </select>
                    </div>
                </div>
            @endif


            @if ($products->isEmpty())
                <div class="text-center p-10 bg-white rounded-lg shadow-lg">
                    <p class="text-xl text-gray-600">No hemos encontrado productos en esta familia por el momento.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($products as $product)
                        <article
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 duration-300">

                            {{-- Imagen del Producto --}}
                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                class="w-full h-52 object-cover object-center">

                            <div class="p-5">

                                {{-- Nombre y Precio --}}
                                <h2 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">{{ $product->name }}</h2>
                                <p class="text-2xl font-extrabold text-pink-600 mb-3">
                                    ${{ number_format($product->price, 2) }}</p>

                                {{-- Indicador de Stock --}}
                                <div class="mb-4 text-sm font-semibold">
                                    @if ($product->stock > 0)
                                        <p class="text-green-600 flex items-center">
                                            <i class="fas fa-check-circle mr-2"></i> Disponible: {{ $product->stock }}
                                            unidades
                                        </p>
                                    @else
                                        <p class="text-red-600 flex items-center">
                                            <i class="fas fa-times-circle mr-2"></i> ¡Agotado! (Sin stock)
                                        </p>
                                    @endif
                                </div>

                                {{-- Botón de Acción --}}
                                @if ($product->stock > 0)
                                    {{-- Si hay stock, redirigimos a la vista de detalle para comprar --}}
                                    <a href="{{ route('products.show', $product) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-500 hover:bg-red-600 transition shadow-md">
                                        <i class="fas fa-shopping-cart mr-2"></i> Ver Producto
                                    </a>
                                @else
                                    {{-- Si no hay stock, se muestra el botón deshabilitado y sin enlace --}}
                                    <button disabled
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-500 bg-gray-200 cursor-not-allowed shadow-inner">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> AGOTADO
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
