<x-admin-layout>
    <x-slot name="action">
        <a href="{{ route('admin.covers.create') }}" class="btn btn-blue">
            <i class="fa-solid fa-plus mr-2"></i> Añadir Nueva Portada
        </a>
    </x-slot>

    <ul class="space-y-4">
        @foreach ($covers as $cover)
            <li class="bg-white rounded-lg shadow-lg overflow-hidden lg:flex">
                <img src="{{ $cover->image }}" alt="image" class="w:full lg:w-64 aspect-[3/1] object-cover object-center">
                <div class="p-4 lg:flex-1 lg:flex lg:justify-between lg:items-center space-y-3 lg:space-y-0">
                    <div>
                        <h1 class="font-bold ">
                            {{ $cover->title }}
                        </h1>

                        <p>
                            @if ($cover->is_active)
                                <span
                                    class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20">Activo</span>
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 inset-ring inset-ring-red-400/20">
                                    Inactivo
                                </span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-small font-bold">
                            Fecha de inicio
                        </p>

                        <p class="text-small font-bold">
                            {{ $cover->start_at->format('d-m-Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-small font-bold">
                            Fecha de finalizacion
                        </p>

                        <p class="text-small font-bold">
                            {{ $cover->end_at ? $cover->end_at->format('d-m-Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <a class="btn btn-red" href="{{ route('admin.covers.edit', $cover) }}">
                            Editar/ Eliminar
                        </a>
                    </div>
                </div>

            </li>
        @endforeach
    </ul>
    <div></div>
</x-admin-layout>
