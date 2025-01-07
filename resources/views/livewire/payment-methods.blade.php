
<div class="w-full">

   <div class="flex flex-col items-center justify-center mx-auto ">




    <h1 class="mt-3 text-lg font-medium text-gray-600 ">Añadir método de pago</h1>
   <div class="transform transition duration-2000 hover:scale-105 w-96 mt-3 card ml-5 p-4 border-[1px] border-gray-300 shadow-lg shadow-gray-500/50">
    <input id="card-holder-name" type="text" class="text-gray-500 mt-2 mb-4 border-[1px] border-gray-400 rounded-lg h-[30px]">

    <!-- Elementos de la tarjeta (añadir) -->
    <div id="card-element"></div>

    <button class="disabled:loading mb-2 transform transition duration-2000 hover:scale-105 w-[70px] mx-auto mt-4 text-gray-500 w btn btn-ghost btn-sm" id="card-button"  class="mt-4" data-secret="{{ $intent->client_secret }}">
        Agregar
    </button>

    <span id="card-error-message" class="text-xs font-medium text-center text-red-600 ">


    </span>

</div>
<script>
    function recargarPagina() {
        // Recarga la página actual
        window.location.reload();
    }
</script>

<h1 class="mt-3 text-lg font-medium text-gray-600 ">Métodos de pago</h1>



    <!-- Acceso a los métodos del cliente -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 ">

@foreach ($paymentMethods as $paymentMethod)




<div class=" transform transition duration-2000 hover:scale-105 w-96 mt-3 card ml-5 p-4 border-[1px] border-gray-300 shadow-lg shadow-gray-500/50 h-52 ">



    <div class="flex flex-row ">
        @php
        $brand = $paymentMethod->card->brand;
        $imagePath = 'storage/' . $brand . '.png';
    @endphp

    <img class="w-16" src="{{ asset($imagePath) }}" alt="Imagen de la tarjeta">


    <div class="ml-auto">
<h1 class="text-gray-500">{{ $paymentMethod->card->funding}}</h1>
</div>

</div>


<h1 class="mt-10 mb-3 text-center text-md text-gray-700">**** **** **** {{ $paymentMethod->card->last4 }}</h1>


<div class="flex text-gray-600">
<h1 class="">{{ $paymentMethod->card->exp_month}}/</h1>
<h1 class="">{{ $paymentMethod->card->exp_year}}</h1>
</div>

<div class="flex">
<h1 class="font-medium text-gray-600">{{ $paymentMethod->billing_details->name }}</h1>

</div>
<button onclick="recargarPagina()" class="ml-auto mt-[-45px]  text-2xl transition transform hover:text-red-700 duration-2000 hover:scale-125" wire:loading.class="loading" wire:click='deletePaymentMethod("{{$paymentMethod->id}}")' >×</button>

</div>

@endforeach
</div>

</div>



    <script src="https://js.stripe.com/v3/"></script>


        <!-- Esto trae los elemntos prehechos de stripe -->

<script>
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');
</script>


    <!-- Este script maneja toda la logica para enviar los datos a stripe y los guarde -->

<script>
const cardHolderName = document.getElementById('card-holder-name');
const cardButton = document.getElementById('card-button');

cardButton.addEventListener('click', async (e) => {


    cardButton.disabled = true;

    const clientSecret = cardButton.dataset.secret;


    const { setupIntent, error } = await stripe.confirmCardSetup(



        clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: { name: cardHolderName.value }
            }
        }
    );


    cardButton.disabled = false;

    if (error) {

        let span = document.getElementById("card-error-message");

        span.textContent = error.message;

    } else {

        window.location.reload();


         cardHolderName.value = " ";

         cardElement.value = "";


        @this.addPaymentMethod(setupIntent.payment_method)


    }
});

</script>

</div>
