<x-app-layout>
    @livewireStyles

    <div class="flex flex-row mx-auto justify-center">
        <div class="grid grid-cols-1">  
            @foreach($carts as $cart)
                @php
                    $decoded_cart = json_decode($cart->cart);
                @endphp


                <div class="flex flex-row">
                    <div class="card border-[1px] border-gray-200 rounded-2xl shadow-lg p-4 w-[550px] mt-3 mx-auto">
                    <button wire:click='testFunction("{{$cart->id}}")' class="ml-auto transform transition duration-3000 hover:scale-105">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
    </svg>
</button>




                        <div class="flex flex-row mt-[-13px]">
                        <img src="{{ asset('storage/' . $images[$cart->id]) }}" alt="Imagen del producto" class="w-40 rounded-2xl transform transition duration-3000 hover:scale-105">

                            <div class="flex flex-col">
                                <h1 class="ml-3 mt-2 font-medium tracking-1 text-lg w-80">{{ $decoded_cart->product->name }}</h1>

                                <h1 class="ml-3 mt-2 font-medium tracking-1 text-2xl w-80">${{ $decoded_cart->amount * $decoded_cart->product->price }}</h1>
                                <h1 class="ml-3 mt-16 font-medium tracking-1 text-md w-80">Color: {{ $decoded_cart->color->color }}</h1>
                                <h1 class="ml-3 mt-2 font-medium tracking-1 text-md w-80">Talla: {{ $decoded_cart->size->size }}</h1>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card border-[1px] border-gray-200 rounded-2xl shadow-2xl p-4 w-[550px] h-80 mt-3 ml-10">
            <div class="flex flex-row">
                <h1>Valor del pedido</h1>
                <h1>$900</h1>




                @livewire("product-pay")
            </div>
        </div>
    </div>
    @livewireScripts

</x-app-layout>
