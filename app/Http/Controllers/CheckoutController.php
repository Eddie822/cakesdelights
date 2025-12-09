<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        // Obtener todas las direcciones del usuario autenticado (opcional, para mostrar en la vista)
        $addresses = Address::where('user_id', auth()->id())->get();

        // Cargar la dirección predeterminada aquí también para la vista
        $defaultAddress = Address::where('user_id', auth()->id())
            ->where('default', 1) // Usamos 1 para la vista
            ->first();

        return view('checkout.index', compact('addresses', 'defaultAddress'));
    }

    public function process(Request $request)
    {
        // Tomar la dirección predeterminada del usuario
        $shippingAddress = Address::where('user_id', auth()->id())
            ->where('default', 1) // CAMBIO CLAVE: Usamos 1 en lugar de true
            ->first();

        // ----------------------------------------------------
        // El resto del código solo se ejecuta si dd() es comentado o eliminado
        // ----------------------------------------------------

        if (!$shippingAddress) {
            // Si no se encuentra, la ejecución se detiene aquí y regresa el error
            return back()->withErrors(['address' => 'No tienes ninguna dirección predeterminada.']);
        }

        // Crear orden con la dirección del usuario autenticado
        // CORRECCIÓN: Limpiamos los valores numéricos quitando la coma ','
        $order = Order::create([
            'user_id' => auth()->id(),
            'address_id' => $shippingAddress->id,
            // subtotal() sin argumentos devuelve string con coma, lo limpiamos
            'subtotal' => (float) str_replace(',', '', Cart::instance('shopping')->subtotal()),
            // tax() devuelve string con coma, lo limpiamos
            'tax' => (float) str_replace(',', '', Cart::instance('shopping')->tax()),
            // total() devuelve string con coma (ej: "6,825.00"), lo limpiamos a "6825.00"
            'total' => (float) str_replace(',', '', Cart::instance('shopping')->total()),
            'status' => 'pending',
        ]);

        // Guardar items del carrito
        foreach (Cart::instance('shopping')->content() as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'product_name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->qty,
            ]);
        }

        // Configurar Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach (Cart::instance('shopping')->content() as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'mxn',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => intval($item->price * 100),
                ],
                'quantity' => $item->qty,
            ];
        }

        // Crear sesión de pago en Stripe
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['order' => $order->id]),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order');
        $order = Order::findOrFail($orderId);

        $order->update(['status' => 'paid']);

        $cartContent = OrderItem::where('order_id', $order->id)->get();
        $total = $order->total;

        Cart::instance('shopping')->destroy();

        return view('checkout.success', compact('order', 'cartContent', 'total'));
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}
