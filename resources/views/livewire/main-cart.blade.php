<div> <h1 class="text-center text-4xl font-black mt-8">Carrito de compra</h1>

    <div id="loadingModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg text-center">
            <h1 class="text-3xl font-bold">Procesando compra...</h1>
            <div class="mt-4">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-solid"></div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1  md:flex mx-auto justify-center mt-8">
        <div class="grid grid-cols-1">  
             @if(count($cartItems) > 0)
            @foreach($cartItems as $item)
                


                <div class="flex flex-row  ">
                    <div class="card border-[1px] border-gray-200 rounded-2xl shadow-lg p-4  h-72  mt-3 mx-auto ">
        



                        <button wire:click="removeFromCart('{{ $item['cart_id'] }}')" class="ml-auto  transform transition duration-3000 hover:scale-105"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                          </svg>
                          </button>

                        <div class="flex flex-row mt-[-23px]">

                            <div class="flex flex-col">
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="Imagen del producto" class="w-40 rounded-lg shadow-lg transform transition duration-3000 hover:scale-105">
                        </div>
                            <div class="flex flex-col ml-3">

                                <div class="ml-3 mt-2 flex flex-row">
                                <h1 class="font-semibold tracking-1 text-2xl w-90">{{ $item['name'] }}</h1>
                                <h1 class="font-semibold tracking-1 text-sm w-90 ml-1"> (x{{ $item['amount'] }})</h1>
                               
                            </div>
                                <h1 class="ml-3 mt-2 font-semibold tracking-1 text-3xl w-90">${{ $item['price'] *  $item['amount'] }}</h1>
                                <h1 class="ml-3 mt-24  tracking-1 font-medium text-md w-80">Color: {{ $item['color'] }}</h1>
                                <h1 class="ml-3 mt-1  tracking-1 font-medium text-md w-80">Talla: {{ $item['size'] }}</h1>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card mx-auto md:mx-1 border-[1px] border-gray-200 rounded-2xl shadow-2xl p-4 w-[400px] h-auto  mt-3 md:ml-14">
            <div class="flex flex-row ml-5 mt-5">
                <h1 class=" text-lg">Valor del pedido</h1>
                <h1 class=" text-lg ml-auto mr-5">${{ $total }}</h1>
            </div>

            <div class="flex flex-row ml-5 mt-8">
                <h1 class=" text-lg">Envío</h1>
                <h1 class=" text-lg ml-auto mr-5">${{$ship}}</h1>
            </div>
           
            @livewire('product-pay', ['total' => $total])
           

        @endif

        </div>
    </div>

    </div>
