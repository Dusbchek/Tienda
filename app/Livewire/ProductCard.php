<?php

namespace App\Livewire;

use App\Traits\CacheType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use App\Traits\ProductCache;

class ProductCard extends Component
{
    use ProductCache;

    /* Datos para enseñar el producto */
    public int $id;
    public string $name;
    public string $description;
    public Collection $colors;
    public int $selectedColor;
    public float $price;
    public string $slug;

    public bool $isFavoriteCacheItem;
    public function getFavorite(): bool
    {
        return $this->isFavoriteCacheItem;
    }

    /* Colecciones dinámicas de las dotos de productos */
    public Collection $imagesCollection; //esta es la colección general de imágenes
    public Collection $selectedImages; //esta es la colección de imágenes escogida por colores

    public function handleFavorite(): void
    {
        if ($this->isFavoriteCacheItem) {
            $this->removeFromCache(CacheType::FAVORITES, $this->id);
        } else {
            $this->addToCache(CacheType::FAVORITES, $this->id);
        }
        $this->isFavoriteCacheItem =!$this->isFavoriteCacheItem;
    }

    public function mount(): void
    {
        $cache = json_decode(cache()->get('favorites_' . Auth::id(), '[]'));
        $this->isFavoriteCacheItem = !empty(
            array_filter($cache, function ($item) { return $item->product_id == $this->id; })
        );

        $this->selectedColor = $this->colors->pluck('id')->first();
        $this->selectedImages = $this->imagesCollection->filter(function ($image){
            return $image->color_id === $this->selectedColor;
        });
    }

    public function render(): View
    {
        return view('livewire.product-card');
    }
}
