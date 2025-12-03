<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\RawMaterial;
use App\Models\MaterialOrder;
use App\Models\MaterialOrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Necesario para la fecha de entrega

class CreateMaterialOrder extends Component
{
    // Propiedades del Encabezado del Pedido
    public $supplierId;
    public $orderTotal = 0;

    // Propiedad dinámica para los ítems del pedido (Maestro-Detalle)
    public array $items = [];

    // Colecciones de datos (Opciones para <select>)
    public Collection $suppliers;
    public Collection $availableMaterials;

    public function mount()
    {
        // Cargar colecciones para los select
        $this->suppliers = Supplier::orderBy('name')->get();
        $this->availableMaterials = RawMaterial::orderBy('name')->get();

        // Inicializar con al menos un ítem vacío si es el primer montaje
        if (empty($this->items)) {
            $this->items[] = $this->getEmptyItem();
        }
    }

    protected function getEmptyItem(): array
    {
        return [
            'raw_material_id' => null,
            'quantity_ordered' => 0,
            'unit_cost' => 0.00,
            'material_unit' => '', // Para mostrar la unidad (kg, L, etc.)
            'subtotal' => 0.00,
        ];
    }

    // Usamos el hook `boot` para la validación de errores al igual que en tu ProductCreate
    public function boot()
    {
        $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Error de Validación',
                    'text' => 'Por favor, corrige los errores en el formulario para poder enviar el pedido.',
                ]);
            }
        });
    }

    // Método que se ejecuta cada vez que hay un cambio en el formulario
    public function updated($field)
    {
        if (preg_match('/^items\.(\d+)\.(raw_material_id|quantity_ordered|unit_cost)$/', $field, $matches)) {
            $index = $matches[1];
            $field_name = $matches[2];

            // 1. Manejar la actualización del material seleccionado (para obtener la unidad)
            if ($field_name === 'raw_material_id') {
                $materialId = $this->items[$index]['raw_material_id'];

                if ($materialId) {
                    $material = $this->availableMaterials->find($materialId);
                    if ($material) {
                        $this->items[$index]['material_unit'] = $material->unit;
                    }
                }
            }

            // 2. Recalcular subtotal y total después de cualquier cambio relevante
            $this->calculateSubtotal($index);
            $this->calculateTotal();
        }
    }

    /**
     * Calcula el subtotal para un ítem específico.
     */
    protected function calculateSubtotal($index)
    {
        $qty = (float) $this->items[$index]['quantity_ordered'];
        $cost = (float) $this->items[$index]['unit_cost'];

        if ($qty > 0 && $cost > 0) {
            $this->items[$index]['subtotal'] = round($qty * $cost, 2);
        } else {
            $this->items[$index]['subtotal'] = 0.00;
        }
    }

    /**
     * Calcula el costo total del pedido sumando todos los subtotales.
     */
    protected function calculateTotal()
    {
        $total = collect($this->items)->sum('subtotal');
        $this->orderTotal = round($total, 2);
    }

    /**
     * Agrega una nueva fila para un ítem de material al pedido.
     */
    public function addItem()
    {
        $this->items[] = $this->getEmptyItem();
    }

    /**
     * Elimina una fila de ítem del pedido.
     */
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reindexar el array
        $this->calculateTotal();
    }

    /**
     * Almacena el pedido y sus ítems directamente desde el componente Livewire.
     * Esto reemplaza la necesidad de enviar el formulario a un controlador tradicional.
     */
    public function saveOrder()
    {
        // 1. Validación (usando las mismas reglas de tu controlador)
        $this->validate([
            'supplierId' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            // Reglas para los ítems
            'items.*.raw_material_id' => [
                'required',
                'exists:raw_materials,id',
                // Validación custom para evitar ítems duplicados
                function ($attribute, $value, $fail) {
                    $materialIds = collect($this->items)->pluck('raw_material_id')->filter()->toArray();
                    if (count($materialIds) !== count(array_unique($materialIds))) {
                        $fail('No se puede seleccionar el mismo material más de una vez en un solo pedido.');
                    }
                }
            ],
            'items.*.quantity_ordered' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0.01',
        ]);

        // 2. Preparar los datos del encabezado
        $expectedDeliveryDate = Carbon::now()->addHours(24);
        $orderData = [
            'supplier_id' => $this->supplierId,
            'user_id' => auth()->id(),
            'total_cost' => $this->orderTotal,
            'status' => 'ordered',
            'expected_delivery_date' => $expectedDeliveryDate,
        ];

        // 3. Preparar los datos de los ítems
        $itemData = collect($this->items)->map(function ($item) {
            return [
                'raw_material_id' => $item['raw_material_id'],
                'quantity_ordered' => $item['quantity_ordered'],
                'unit_cost' => $item['unit_cost'],
            ];
        })->toArray();

        // 4. Guardar en la base de datos dentro de una transacción
        DB::transaction(function () use ($orderData, $itemData) {
            $order = MaterialOrder::create($orderData);
            $order->items()->createMany($itemData);
        });

        // 5. Emitir notificación y redireccionar
        session()->flash('success', 'Pedido creado y enviado. Entrega esperada: ' . $expectedDeliveryDate->format('d/m/Y H:i'));
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Pedido Enviado',
            'text' => 'El pedido se ha enviado al proveedor y el inventario se actualizará al recibirlo.',
        ]);

        return redirect()->route('admin.material_orders.index');
    }

    public function render()
    {
        return view('livewire.admin.create-material-order');
    }
}
