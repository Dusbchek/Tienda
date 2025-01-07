<div>

<div class="rounded-xl p-2 border-[1px] border-gray-300 shadow-md mt-4 mb-2">
    <div class="">

<h1 class="text-xl font-bold text-center">Métodos de pago</h1>

@foreach ($paymentMethods as $paymentMethod)

@php
$brand = $paymentMethod->card->brand;
$imagePath = 'storage/' . $brand . '.png';
@endphp


<label>
    <div class="flex flex-row items-center">
        
<input  wire:model='paymentMethod' name="paymentMethod" type="radio" value="{{ $paymentMethod->id }}" >
<h1 class="truncate w-[152px] ml-1">{{ $paymentMethod->billing_details->name }} </h1>
<h1 class="ml-auto">****{{ $paymentMethod->card->last4 }}</h1>
<img class="w-10 ml-1" src="{{ asset($imagePath) }}" alt="Imagen de la tarjeta">

</input>
</div>
</label>
@endforeach
    </div>
</div>
<div class="border-[1px] mt-10 border-blue-800 rounded-full ml-2 mr-2"></div>
<div class="flex flex-row ml-5 mt-8">
    <h1 class=" text-2xl font-bold">Total</h1>
    <h1 class=" text-2xl font-bold ml-auto mr-5">${{ $total + 160}} </h1>
</div>

<div class="flex mx-auto justify-center">
<button wire:click='purchase' class="rounded-full border-[1px] text-lg border-gray-500 py-1 px-3 my-1 font-bold hover:bg-gray-200" wire:loading.class="loading">Pagar</button>
</div>
</div>
