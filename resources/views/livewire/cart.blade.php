
<div>
<button wire:click="addToCart" class="btn rounded-full py-1 transform transition duration-2000 hover:scale-105">
        Agregar 🛒
    </button>
<!-- resources/views/livewire/cart.blade.php -->
<div>
    <p>User ID: {{ $userId }}</p>
    
    <p>Product: {{ $cartItem['product']->name }}</p>
    <p>Size: {{ $cartItem['size'] }}</p>
    <p>Color: {{ $cartItem['color'] }}</p>
    <p>Amount: {{ $cartItem['amount'] }}</p>
</div>

</div>