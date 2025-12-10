<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class OrdersController extends Controller
{
    /**
     * Muestra una lista paginada de órdenes.
     */
    public function index()
    {
        // Precargamos 'user' para la tabla
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Muestra el detalle de una orden específica (show).
     */
    public function show(Order $order)
    {
        // Precarga las relaciones necesarias para la vista de detalle
        $order->load('items.product', 'user', 'address');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Actualiza el estado de la orden (Logística: Pagar -> Enviar -> Entregar).
     */
    public function updateStatus(Request $request, Order $order)
    {
        // 1. Validación básica del estado
        $request->validate([
            'status' => 'required|in:paid,shipped,delivered,canceled'
        ]);

        $newStatus = $request->status;
        $updateData = ['status' => $newStatus];

        // Lógica: Si pasa de PAGADO a ENVIADO, o de ENVIADO a ENTREGADO
        if ($order->status === 'paid' && $newStatus === 'shipped') {
            // Acción: El administrador marca el pedido como listo para envío
            // No hacemos nada más aquí, ya que la fecha estimada se calculó en checkout.
        } elseif ($order->status === 'shipped' && $newStatus === 'delivered') {
            // Si el admin marca como entregado, registra la fecha real de entrega.
            $updateData['delivery_date'] = Carbon::today();
        }

        try {
            $order->update($updateData);
        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar el estado de la orden: ' . $e->getMessage());
        }

        return back()->with('success', 'Estado de la orden actualizado a ' . $newStatus);
    }

    /**
     * Elimina una orden de compra del cliente.
     */
    public function destroy(Order $order)
    {
        try {
            // La eliminación en cascada en la base de datos debería eliminar también los order_items.
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Orden #' . $order->id . ' eliminada correctamente.');
        } catch (Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'No se pudo eliminar la orden: ' . $e->getMessage());
        }
    }
}
