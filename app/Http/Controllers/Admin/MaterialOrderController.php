<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialOrder;
use App\Models\RawMaterial;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Necesario para la transacción
use Carbon\Carbon;

class MaterialOrderController extends Controller
{
    /**
     * Muestra una lista paginada de pedidos de materia prima.
     */
    public function index()
    {
        $orders = MaterialOrder::with('supplier', 'user')
                            ->latest()
                            ->paginate(10);

        return view('admin.material_orders.index', compact('orders'));
    }

    /**
     * Muestra el formulario para crear un nuevo pedido.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.material_orders.create', compact('suppliers'));
    }

    /**
     * Almacena un nuevo pedido en la base de datos (Calcula la fecha de entrega).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'total_cost' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.raw_material_id' => 'required|exists:raw_materials,id',
            'items.*.quantity_ordered' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated) {

            $expectedDeliveryDate = Carbon::now()->addHours(24);

            $order = MaterialOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'total_cost' => $validated['total_cost'],
                'status' => 'ordered',
                'expected_delivery_date' => $expectedDeliveryDate,
            ]);

            $order->items()->createMany($validated['items']);

            session()->flash('success', 'Pedido creado y enviado. Entrega esperada: ' . $expectedDeliveryDate->format('d/m/Y H:i'));
        });


        return redirect()->route('admin.material_orders.index');
    }

    /**
     * Lógica para actualizar el estado a 'received' (recibido) y SUMAR STOCK. (Manual)
     */
    public function receive(MaterialOrder $order)
    {
        if ($order->status !== 'received') {

            DB::transaction(function () use ($order) {

                // 1. Cambiar el estado a Recibido (Debe ser lo primero)
                $order->update(['status' => 'received']);

                // 2. LÓGICA CLAVE: SUMAR STOCK A raw_materials
                foreach ($order->items as $item) {

                    // Usamos findOrFail para lanzar una excepción visible si el material fue eliminado.
                    $material = RawMaterial::findOrFail($item->raw_material_id);

                    // Aseguramos que la cantidad sea numérica para el incremento
                    $quantity = (float) $item->quantity_ordered;

                    // Si el sistema falla aquí, significa que la columna 'current_stock' no es DECIMAL/FLOAT
                    // o que el valor de $quantity es inválido.
                    $material->increment('current_stock', $quantity);
                }
            });

            return back()->with('success', 'Pedido marcado como Recibido. ¡Stock de almacén actualizado!');
        }

        return back()->with('info', 'El pedido ya había sido marcado como Recibido.');
    }

    /**
     * Muestra el detalle de un pedido específico.
     */
    public function show(MaterialOrder $material_order)
    {
        $material_order->load('items.material');

        return view('admin.material_orders.show', compact('material_order'));
    }

    // ... (Métodos edit, update y destroy omitidos por simplicidad) ...
}
