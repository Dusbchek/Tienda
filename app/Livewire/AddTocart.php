<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Image;
use App\Models\images;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddTocart extends Component
{
    public $carts;
    public $images = [];
    public $Idcart = [];  // Declarar la variable para los IDs

   
    public function deleteCart($cartId)
    {
        // Eliminar el carrito con el ID dado
        Cart::where('id', $cartId)->delete();

        // Actualizar la lista de carritos
        $this->render();
    }

    public function render()
    {
        $userId = Auth::id();
        $carts = Cart::where('user_id', $userId)->get();

        // Obtener los IDs de los carts
        $Idcart = $carts->pluck('id')->toArray();

        // Obtener las imágenes correspondientes
        $images = [];
        foreach ($carts as $cart) {
            $productId = $cart->product_id;
            $images[$productId] = images::where('products_id', $productId)->pluck('image')->first();
        }

        return view('livewire.add-tocart', [
            'carts' => $carts,
            'images' => $images,
            'Idcart' => $Idcart,  // Pasar los IDs a la vista
        ]);
    }
}
