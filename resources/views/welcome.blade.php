<x-app-layout>

    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
    @endpush

    <div class="min-h-screen bg-[#f3e7d9]"> {{-- color crema pastel suave --}}

        <!-- Slider main container -->
        <div class="swiper mb-12">
            <div class="swiper-wrapper">
                @foreach ($covers as $cover)
                    <div class="swiper-slide relative">
                        <!-- Imagen de fondo -->
                        <img src="{{ $cover->image }}" alt="{{ $cover->title }}"
                            class="w-full aspect-[3/1] object-cover object-center">

                        <!-- Capa de oscurecimiento -->
                        <div class="absolute inset-0 bg-black/50"></div>

                        <!-- Contenido centrado -->
                        <div
                            class="absolute inset-0 flex flex-col justify-center items-center text-center text-white px-6">
                            <h2 class="text-3xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
                                {{ $cover->title ?? 'Título de la portada' }}
                            </h2>

                            <p class="text-lg md:text-xl mb-6 max-w-2xl leading-relaxed drop-shadow-md">
                                Déjate tentar por nuestros exquisitos sabores y delicias horneadas con amor
                            </p>

                            <a href="{{ route('welcome.index') }}"
                                class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white text-lg font-semibold rounded-full transition-all shadow-md">
                                Ver productos
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación y navegación -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div>
        <div class="container mx-auto px-6">

            <h1 class="text-center text-2xl font-bold text-gray-700 mb-4">
                Promociones Especiales
            </h1>

            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach ($lastProducts as $product)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ $product->image }}" class="w-full h-48 object-cover object-center"
                            class="w-full h-48 object-cover object-center">

                        <div class="p-4">
                            <h1 class="text-lg font-semibold text-gray-800 line-clamp-2 min-h-[56]:">
                                {{ $product->name }}
                            </h1>

                            <p class="text-gray-600 mt-2 mb-4 line-clamp-1">

                                $ {{ $product->price }}
                            </p>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $product->description ?? 'Delicioso producto artesanal recién hecho.' }}
                            </p>

                            <a href="{{ route('products.show', $product) }}" class="btn btn-orange mb-4">
                                Ver mas
                            </a>
                        </div>

                    </article>

                    {{-- Nuestros productos --}}
                @endforeach

            </div>

        </div>

        {{-- Sección de productos destacados --}}
        <section class="py-16 bg-[#f3e7d9] mb-16">
            <<div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">
                    Nuestros Productos
                </h2>

                <!-- Línea decorativa -->
                <div class="w-16 h-1 bg-yellow-400 mx-auto mb-10 rounded-full"></div>

                <!-- Filtros de familias -->
                <div class="flex justify-center space-x-4 mb-10">
                    <button
                        class="bg-red-400 text-white px-6 py-2 rounded-full shadow-md hover:bg-red-500 transition">Todos</button>
                    <button
                        class="bg-white text-gray-800 px-6 py-2 rounded-full shadow hover:bg-yellow-100 transition">Pasteles</button>
                    <button
                        class="bg-white text-gray-800 px-6 py-2 rounded-full shadow hover:bg-yellow-100 transition">Postres</button>
                    <button
                        class="bg-white text-gray-800 px-6 py-2 rounded-full shadow hover:bg-yellow-100 transition">Cupcakes</button>
                </div>

                <!-- Grid de productos -->
                <div class=" grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($lastProducts as $product)
                        <div
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                class="w-full h-52 object-cover object-center">

                            <div class="p-5 text-left">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $product->description ?? 'Delicioso producto artesanal recién hecho.' }}</p>

                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-yellow-600 font-bold">${{ number_format($product->price, 2) }}</span>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-orange mb-4">
                                        Ver mas
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        @push('js')
            <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

            <script>
                const swiper = new Swiper('.swiper', {
                    // Optional parameters
                    loop: true,

                    autoplay: {
                        delay: 5000,
                    },

                    // If we need pagination
                    pagination: {
                        el: '.swiper-pagination',
                    },

                    // Navigation arrows
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            </script>
        @endpush
</x-app-layout>
