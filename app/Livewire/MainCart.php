<?php

namespace App\Livewire;

use App\Traits\ProductCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\CacheType; // Add this at the top with other imports
use App\Traits\CartCacheKey;


class MainCart extends Component
{




    use ProductCache;
    public $cartItems = [];
    public $total = 0;


    public $ship = 160;

    public function mount()
    {
        $userId = Auth::id();
        $cartJson = cache()->get('cart_' . $userId, '[]');
        $cart = json_decode($cartJson, true);

        // Para cada elemento del carrito, obtener la primera imagen, el precio, el nombre, el tamaño y el color correspondiente
        foreach ($cart as &$item) {
            $image = DB::table('images')
                ->where('products_id', $item['product_id'])
                ->value('image'); // Obtener solo la primera imagen

            $price = DB::table('products')
                ->where('id', $item['product_id'])
                ->value('price'); // Obtener el precio del producto

            $name = DB::table('products')
                ->where('id', $item['product_id'])
                ->value('name'); // Obtener el nombre del producto

            $size = DB::table('sizes')
                ->where('id', $item['size'])
                ->value('size'); // Obtener el tamaño del producto

            $color = DB::table('colors')
                ->where('id', $item['color'])
                ->value('color'); // Obtener el color del producto

            $item['image'] = $image;
            $item['price'] = $price;
            $item['name'] = $name;
            $item['size'] = $size; // Asignar el tamaño obtenido
            $item['color'] = $color; // Asignar el color obtenido

            // Calcular el total
            $this->total += $item['price'] * $item['amount'];
        }

        $this->cartItems = $cart;
    }


    public function removeFromCart($cartId)
    {
        $this->removeFromCache(CacheType::CART, $cartId);
        $this->mount();
    }

    public function render()
    {
        return view('livewire.main-cart', [
            'cartItems' => $this->cartItems,
            'total' => $this->total,
            'ship' => $this->ship
        ]);
    }
}
