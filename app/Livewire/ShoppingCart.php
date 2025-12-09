<?php

namespace App\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use App\Models\Product; // Importamos el modelo Product

class ShoppingCart extends Component
{

    public function increase($rowId)
    {
        Cart::instance('shopping');

        $item = Cart::get($rowId); // Obtener el ítem actual del carrito

        // 1. Cargar el producto del catálogo para obtener el stock más reciente
        // Esto es esencial si la asociación falla o el stock cambia después de que se agregó al carrito
        $product = Product::find($item->id);

        if (!$product) {
            // Si el producto no se encuentra en el catálogo, no permitir aumentar
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Producto no encontrado',
                'text' => 'Este producto ya no está disponible en el catálogo.',
            ]);
            return;
        }

        // 2. Verificamos que la cantidad sea menor al stock del producto real
        if ($item->qty < $product->stock) {

            // Si hay stock, aumentar la cantidad
            Cart::update($rowId, $item->qty + 1);

            if (auth()->check()) {
                Cart::store(auth()->id());
            }

            $this->dispatch('cartUpdated', Cart::count()); //(1)

        } else {
            // Si el modelo existe, pero el stock es insuficiente (se alcanza el límite)
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Stock Insuficiente',
                'text' => 'Solo hay ' . $product->stock . ' unidades disponibles de este producto.',
            ]);
        }
    }


    public function decrease($rowId)
    {
        Cart::instance('shopping');

        $item = Cart::get($rowId);

        if ($item->qty > 1){
            Cart::update($rowId, $item->qty - 1);
        }else {
            Cart::remove($rowId);
        }

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        $this->dispatch('cartUpdated', Cart::count()); //(1)
    }

    public function remove($rowId)
    {
        Cart::instance('shopping');
        Cart::remove($rowId);

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        $this->dispatch('cartUpdated', Cart::count()); //(1)
    }

    public function destroy()
    {
        Cart::instance('shopping');
        Cart::destroy();

        if (auth()->check()) {
            Cart::store(auth()->id());
        }

        $this->dispatch('cartUpdated', Cart::count()); //(1)
    }

    public function mount()
    {
        Cart::instance('shopping');
    }

    public function render()
    {

        return view('livewire.shopping-cart');
    }
}
