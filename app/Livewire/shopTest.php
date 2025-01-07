<?php

namespace App\Livewire;

use App\Models\products;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\View\View;

#[Layout('layouts.app')]
class shopTest extends Component
{
    public Collection $products;

    public function mount(): void
    {
        $this->products = products::where('is_visible', 1)->get();
    }

    public function render(): View
    {
        return view('livewire.shopTest');
    }
}
