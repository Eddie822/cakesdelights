<x-app-layout>
    <div class="min-h-screen bg-[#f3e7d9] py-10">
        <div class="container mx-auto px-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                {{ $family->name }}
            </h1>

            @if ($products->isEmpty())
                <p class="text-center text-gray-600">No hay productos en esta familia.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden">
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-48 object-cover object-center">

                            <div class="p-4">
                                <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h2>
                                <p class="text-gray-600 mb-3">${{ number_format($product->price, 2) }}</p>
                                <a href="#" class="btn btn-orange">Ver más</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
