<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\WelcomeController;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');

Route::get('families/{family}', [FamilyController::class, 'show'])->name('families.show');

Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::get('shipping', [ShippingController::class, 'index'])->name('shipping.index');
// Mostrar la pagina de pago de checkout
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
// Crear la sesión de pago con Stripe
Route::post('checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

Route::get('checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');


//vista de nosotros
Route::get('/nosotros', function () {
    return view('nosotros'); // Llama al archivo nosotros.blade.php
})->name('nosotros.index');

Route::get('prueba', function () {
    Cart::instance('shopping');

    return Cart::content();
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ====================================================================
    // RUTAS DE PEDIDOS DEL CLIENTE
    // ====================================================================
    // Muestra el historial de pedidos del cliente
    Route::get('/mis-pedidos', [OrderController::class, 'history'])->name('user.orders.history');

    // Acción para que el cliente marque el pedido como entregado (delivered)
    Route::post('/pedidos/{order}/delivered', [OrderController::class, 'markAsDelivered'])->name('user.orders.markAsDelivered');
});
