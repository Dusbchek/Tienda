<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class ItemCarrusel extends Component
{
    public Collection $products;

    public function render(): View
    {
        return view('livewire.item-carrusel');
    }
}
