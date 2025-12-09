<div>
    <header class="bg-gradient-to-r from-pink-500 to-yellow-400 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <!-- Logo -->
            <a href="{{ route('welcome.index') }}" class="flex items-center gap-3">
                <img src="{{ asset('img/logo.jpeg') }}" alt="Logo" class="h-12 w-auto rounded-full shadow-md">
                <div>
                    <h1 class="text-white font-bold text-xl md:text-2xl tracking-wide">caKes & delights</h1>
                    <p class="text-white/90 italic text-sm">Dulces momentos en cada bocado</p>
                </div>
            </a>

            <!-- Navegación -->
            <nav x-data="{ open: false }" class="relative">
                <!-- Botón móvil -->
                <button @click="open = !open" class="md:hidden text-white focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>

                <!-- Links -->
                <ul :class="open ? 'flex' : 'hidden md:flex'"
                    class="absolute md:static top-14 right-0 bg-white md:bg-transparent flex-col md:flex-row shadow-md md:shadow-none
                       text-gray-700 md:text-white gap-3 md:gap-6 px-6 md:px-0 py-4 md:py-0 rounded-md md:rounded-none z-40">

                    <li><a href="{{ route('welcome.index') }}" class="hover:text-yellow-200 transition">Inicio</a></li>
                    <li>
                        <a href="{{ route('families.show', '1') }}" class="hover:text-yellow-200 transition">
                            Pasteles
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('families.show', '2') }}" class="hover:text-yellow-200 transition">
                            Postres
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('families.show', '3') }}" class="hover:text-yellow-200 transition">
                            Cupcakes
                        </a>
                    </li>
                    <li><a href="{{ route('nosotros.index') }}" class="hover:text-yellow-200 transition">Nosotros</a></li>

                    <!-- Ícono de login -->

                    {{-- Dropdown --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="p-2 rounded-full bg-white/20 border border-white/30 text-white hover:bg-white/30 hover:scale-110 transition transform">
                                <i class="fas fa-user text-lg"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @guest
                                <div class="px-4 py-2">
                                    <div class="flex justify-center">
                                        <a href="{{ route('login') }}" class="btn btn-orange">
                                            Iniciar Sesión
                                        </a>
                                    </div>
                                    <p class="text-sm text-center mt-3">
                                        ¿No tienes una cuenta?
                                        <a href="{{ route('register') }}" class="text-blue-500 hover:underline">
                                            Regístrate
                                        </a>
                                    </p>
                                </div>
                            @else
                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    Mi perfil
                                </x-dropdown-link>

                                <div class="border-t border-gray-200">
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf

                                        <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>

                                </div>


                            @endguest


                        </x-slot>
                    </x-dropdown>




                    {{-- Agregar ruta para que el usuario pueda ver sus pedidos --}}
                    <!-- Ícono de pedidos -->
                    <li class="relative cursor-pointer">
                        <i class="fas fa-clipboard-list text-lg"></i>
                        <span
                            class="absolute -top-2 -right-2 bg-yellow-300 text-xs font-bold text-gray-800 rounded-full px-1.5"></span>
                    </li>

                    <!-- Ícono de carrito -->
                    <a href="{{ route('cart.index') }}" class="relative">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span id="cart-count"
                            class="absolute -top-2 -right-2 bg-yellow-300 text-xs font-bold text-gray-800 rounded-full px-1.5">
                            {{ Cart::instance('shopping')->count() }}
                        </span>
                    </a>
                </ul>
            </nav>
        </div>
    </header>
    <script src="https://kit.fontawesome.com/6a3264df77.js" crossorigin="anonymous"></script>
</div>
@push('js')
    <script>
        Livewire.on('cartUpdated', (count) => {
            document.getElementById('cart-count').innerText = count;
        });
    </script>
@endpush
