@php
    // Sidebar partial for admin layout
    // Menú lateral del panel administrativo (rutas temporales excepto Dashboard)

    $links = [
        [
            'icon' => 'fa-solid fa-gauge',
            'name' => 'Dashboard',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            //Familias de productos
            'icon' => 'fa-solid fa-box-open',
            'name' => 'Familias',
            'route' => route('admin.families.index'),
            'active' => request()->routeIs('admin.families.*'),
        ],
        [
            //Categorías de productos
            'icon' => 'fa-solid fa-tags',
            'name' => 'Categorías',
            'route' => route('admin.categories.index'),
            'active' => request()->routeIs('admin.categories.*'),
        ],
        [
            //Subcategorías de productos
            'icon' => 'fa-solid fa-tag',
            'name' => 'Subcategorías',
            'route' => route('admin.subcategories.index'),
            'active' => request()->routeIs('admin.subcategories.*'),
        ],
        [
            'icon' => 'fa-solid fa-cake-candles',
            'name' => 'Productos',
            'route' => route('admin.products.index'),
            'active' => request()->routeIs('admin.products.*'),
        ],
        [
            'icon' => 'fa-solid fa-warehouse',
            'name' => 'Almacén',
            'route' => '#',
            'active' => false,
        ],
        [
            'icon' => 'fa-solid fa-truck-ramp-box',
            'name' => 'Pedidos Materia Prima',
            'route' => '#',
            'active' => false,
        ],
        [
            'icon' => 'fa-solid fa-basket-shopping',
            'name' => 'Órdenes', // antes: Pedidos Clientes
            'route' => route('admin.orders.index'),
            'active' => request()->routeIs('admin.orders.*'),
        ],
        [
            'icon' => 'fa-solid fa-globe',
            'name' => 'Contenido Web',
            'route' => route('admin.covers.index'),
            'active' => request()->routeIs('admin.covers.*'),
        ],
        [
            'icon' => 'fa-solid fa-users',
            'name' => 'Usuarios',
            'route' => route('admin.users.index'),
            'active' => request()->routeIs('admin.users.*'),
        ],
    ];
@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full
           bg-white border-r border-gray-200 sm:translate-x-0
           dark:bg-gray-800 dark:border-gray-700 flex flex-col"
    :class="{
        'translate-x-0': sidebarOpen,
        '-translate-x-full': !sidebarOpen
    }"
    aria-label="Sidebar">

    <!-- Contenedor scroll interno -->
    <div class="flex-1 overflow-y-auto px-3 pb-4 pt-20">
        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                <li>
                    <a href="{{ $link['route'] }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white
                               hover:bg-gray-100 dark:hover:bg-gray-700 group
                               {{ $link['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                        <span class="inline-flex w-6 h-6 justify-center items-center">
                            <i class="{{ $link['icon'] }}"></i>
                        </span>
                        <span class="ml-2">{{ $link['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>
