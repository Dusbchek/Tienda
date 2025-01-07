<?php
namespace App\Livewire;

use App\Models\cart as ModelsCart;
use App\Models\products;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Cart extends Component
{
    public $color;
    public $size;
    public $amount = 1;
    public $product; 



 protected $listeners = ['reloadComponent' => 'reloadComponent'];

 public function reloadComponent()
 {
     // Aquí puedes agregar cualquier lógica adicional que necesites
     // Esto reiniciará todas las propiedades del componente
     $this->color = $this->color;
     $this->size = $this->size;
     $this->amount = $this->amount;
 }


    public function addToCart()
    {
        $userId = Auth::id();
        
        $cartItem = [
            'product_id' => $this->product->id, 
            'product' => $this->product, 
            'size' => $this->size,
            'color' => $this->color,
            'amount' => $this->amount,
        ];
    
        ModelsCart::create([
            'user_id' => $userId,
            'product_id' => $this->product->id, 
            'cart' => json_encode($cartItem),
        ]);

        session()->flash('message', 'Producto agregado al carrito con éxito.');


    }

    // ComponenteInicial.php


    public function render()
    {
        $userId = Auth::id();
        
        $cartItem = [
            'product_id' => $this->product->id, 
            'product' => $this->product, 
            'size' => $this->size,
            'color' => $this->color,
            'amount' => $this->amount,
        ];
        
        return view('livewire.cart', [
            'userId' => $userId,
            'cartItem' => $cartItem,
        ]);
    }
}
