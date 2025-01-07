<?php

namespace App\Livewire;

use Livewire\Component;

class PaymentMethods extends Component
{
    public function render()
    {
        return view('livewire.payment-methods',[

            'intent' => auth()->user()->createSetupIntent(),
            "paymentMethods" => auth()->user()->paymentMethods(),

        ]);
    }

    public function deletePaymentMethod($paymentMethod){


        auth()->user()->deletePaymentMethod($paymentMethod);
    }

    public function addPaymentMethod($paymentMethod){

        auth()->user()->addPaymentMethod($paymentMethod);

    }
}
