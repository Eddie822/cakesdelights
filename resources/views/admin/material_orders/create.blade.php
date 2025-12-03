<x-admin-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-3">Nuevo Pedido de Materia Prima</h1>

        {{-- 1. Formulario oculto que Livewire usará para enviar datos al controlador --}}
        {{-- Es crucial que este formulario exista para el envío POST --}}
        <form id="order-form" action="{{ route('admin.material_orders.store') }}" method="POST" class="hidden">
            @csrf
            {{-- Los campos serán llenados dinámicamente con JS --}}
        </form>

        {{-- 2. INTEGRACIÓN DEL COMPONENTE LIVEWIRE --}}
        {{-- Aquí se renderiza el formulario dinámico --}}
        @livewire('admin.create-material-order')
    </div>

    {{-- 3. EL SCRIPT DE INTEGRACIÓN ES CLAVE --}}
    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
             // Escucha el evento 'submit-order-form' emitido por la clase PHP de Livewire
            Livewire.on('submit-order-form', (payload) => {
                const form = document.getElementById('order-form');

                // Limpiar campos existentes (solo deja el token CSRF)
                form.innerHTML = `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">`;

                // Agregar campos de encabezado
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="supplier_id" value="${payload.supplier_id}">`);
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="total_cost" value="${payload.total_cost}">`);

                // Agregar campos de ítems (como un array 'items') para que Laravel los valide y procese
                payload.items.forEach((item, index) => {
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][raw_material_id]" value="${item.raw_material_id}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][quantity_ordered]" value="${item.quantity_ordered}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][unit_cost]" value="${item.unit_cost}">`);
                });

                // Enviar el formulario tradicional al controlador Laravel
                form.submit();
            });
        });
    </script>
    @endpush
</x-admin-layout>
