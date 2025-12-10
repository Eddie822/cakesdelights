<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    /**
     * Muestra el historial de pedidos del cliente autenticado.
     */
    public function history()
    {
        // Carga los pedidos del usuario autenticado, ordenados por fecha, y pre-cargando los ítems
        $orders = Order::where('user_id', Auth::id())
            ->where('status', '!=', 'draft') // Excluir borradores o carritos temporales
            ->with('items.product')
            ->latest()
            ->paginate(10);

        // La vista para esta ruta es 'orders.history'
        return view('orders.history', compact('orders'));
    }

    /**
     * Permite al cliente marcar su pedido como entregado (delivered).
     */
    public function markAsDelivered(Order $order)
    {
        // 1. Verificación de seguridad: solo el dueño puede marcar como entregado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Acceso no autorizado.');
        }

        // 2. Verificar estado: solo se puede marcar como DELIVERED si está SHIPPED
        if ($order->status !== 'shipped') {
            return back()->with('error', 'El pedido debe estar en estado "Enviado" para confirmar la recepción.');
        }

        // 3. Actualizar estado y fecha de entrega
        $order->update([
            'status' => 'delivered',
            'delivery_date' => now(), // Registramos la fecha de confirmación
        ]);

        return back()->with('success', 'Entrega confirmada. ¡Gracias por tu compra!');
    }
}
