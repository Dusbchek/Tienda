<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Cache;


class ProductPay extends Component
{
    public int $total;
    public $product;

    public $paymentMethod;

    public function purchase() {
        auth()->user()->charge($this->total * 100, $this->paymentMethod, [
            'return_url' => url('/dashboard')
        ]);
        
        Cache::forget('cart_' . auth()->id()); 
                
        return redirect('/thanks');
    }
    


    public function render()
    {
        return view('livewire.product-pay', [
            'paymentMethods' => auth()->user()->paymentMethods()
            ]);
    }
}
