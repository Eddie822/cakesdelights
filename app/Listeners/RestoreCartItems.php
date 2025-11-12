<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Gloudemans\Shoppingcart\Facades\Cart;

class RestoreCartItems
{
    /**
     * Maneja el evento de login del usuario.
     */
    public function handle(Login $event): void
    {
        // Limpia el carrito actual de sesión
        Cart::instance('shopping')->destroy();

        // Restaura el carrito guardado del usuario desde la base de datos
        Cart::instance('shopping')->restore($event->user->id);
    }
}
