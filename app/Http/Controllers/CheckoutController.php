<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Agregamos DB para la transacción

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
            ->where('default', 1)
            ->first();

        if (!$shippingAddress) {
            return back()->withErrors(['address' => 'No tienes ninguna dirección predeterminada.']);
        }

        // Limpiamos los valores monetarios de la coma (,)
        $subtotal = (float) str_replace(',', '', Cart::instance('shopping')->subtotal());
        $tax = (float) str_replace(',', '', Cart::instance('shopping')->tax());
        $total = (float) str_replace(',', '', Cart::instance('shopping')->total());


        $order = Order::create([
            'user_id' => auth()->id(),
            'address_id' => $shippingAddress->id,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending', // Inicialmente Pendiente de pago
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

        // Si la orden ya fue procesada, simplemente redireccionar
        if ($order->status !== 'pending') {
            return view('checkout.success', compact('order'))->with('info', 'El pago ya fue procesado.');
        }

        // Lógica de transacción: Asegura que el estado y el stock se actualicen juntos
        DB::transaction(function () use ($order) {

            // Calculamos la fecha de entrega esperada (2 días después de la compra)
            $deliveryDate = Carbon::now()->addDays(2)->toDateString();

            // 1. Actualizar el estado de la orden y la fecha de entrega
            $order->update([
                'status' => 'shipped', // Pasa directamente a Enviado
                'delivery_date' => $deliveryDate,
            ]);

            // 2. LÓGICA CLAVE: DECREMENTAR STOCK DEL PRODUCTO
            foreach ($order->items as $item) {
                // Usamos el item->product para acceder al modelo Product
                $product = $item->product;

                // Si el producto existe y tiene suficiente stock, se descuenta.
                // (Aunque la validación de stock debe hacerse ANTES de checkout, se hace aquí por seguridad)
                if ($product && $product->stock >= $item->quantity) {
                    $product->decrement('stock', $item->quantity);
                } else {
                    // Opcional: Lógica para manejar stock negativo o cancelar/marcar la orden como fallida
                    // Por simplicidad, si el stock es 0, no hace nada o podrías lanzar una excepción aquí:
                    // throw new Exception('Stock insuficiente para el producto ' . $product->name);
                }
            }
        });

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
