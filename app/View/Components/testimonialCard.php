<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class testimonialCard extends Component
{
    public int $rate;
    public string $name;

    /**
     * Create a new component instance.
     */
    public function __construct($rate = 1, $name = "Jhon Doe")
    {
        $this->rate = $rate;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.landing-store.testimonial-card');
    }
}
