<?php

namespace App\Livewire;

use App\Models\categories;
use App\Models\colors;
use App\Models\products;
use App\Models\sizes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Shop extends Component
{
    public string $filterTitle = "Lo Nuevo";

    public Collection $products;
    public Collection $sizes;
    public Collection $colors;
    public Collection $categories;

    public ?int $minPriceFilter = null;
    public ?int $maxPriceFilter = null;

    public array $sizesFilter = [];
    public array $colorsFilter = [];
    public array $categoriesFilter = [];

    public function rules(): array
    {
        return [
            'minPriceFilter' => 'nullable|integer|gte:0',
            'maxPriceFilter' => $this->minPriceFilter !== null
                ? 'nullable|integer|gte:0|gt:minPriceFilter'
                : 'nullable|integer|gte:0'
        ];
    }

    public function messages(): array
    {
        return [
            'minPriceFilter.integer' => 'Por favor, proporciona un precio mínimo válido.',
            'minPriceFilter.gte' => 'El precio mínimo debe ser mayor o igual a 0.',
            'maxPriceFilter.integer' => 'Por favor, proporciona un precio máximo válido.',
            'maxPriceFilter.gte' => 'El precio máximo debe ser mayor o igual a 0.',
            'maxPriceFilter.gt' => 'El precio máximo debe ser mayor o igual al precio mínimo.',
        ];
    }

    private function applyFeaturesFilterToQuery($query, $filter, $relation): void
    {
        if (!empty($filter)) {
            $query->whereHas($relation, function ($q) use ($filter, $relation) {
                $q->whereIn($relation.'_id', $filter);
            });
        }
    }

    private function updateFilterTitle(): void
    {
        $filters = array_filter([
            $this->minPriceFilter !== null ? 'Precio' : null,
            $this->maxPriceFilter !== null ? 'Precio' : null,
            !empty($this->sizesFilter) ? 'Tallas' : null,
            !empty($this->colorsFilter) ? 'Colores' : null,
            !empty($this->categoriesFilter) ? 'Categorías' : null,
        ]);

        if (count($filters) === 0) {
            $this->filterTitle = "Lo Nuevo";
        } elseif (count($filters) === 1) {
            $this->filterTitle = "Por " . reset($filters);
        } else {
            $this->filterTitle = "Búsquedas encontradas";
        }
    }

    public function applyFilter(): void
    {
        $this->validate();

        $query = products::where('is_visible', 1);

        if ($this->minPriceFilter !== null) {
            $query->where('price', '>=', $this->minPriceFilter);
        }

        if ($this->maxPriceFilter !== null) {
            $query->where('price', '<=', $this->maxPriceFilter);
        }

        $this->applyFeaturesFilterToQuery($query, $this->sizesFilter, 'sizes');
        $this->applyFeaturesFilterToQuery($query, $this->colorsFilter, 'colors');
        $this->applyFeaturesFilterToQuery($query, $this->categoriesFilter, 'categories');

        $this->products = $query->get();
        $this->updateFilterTitle();  // Actualizar el título del filtro después de aplicar los filtros
    }

    public function mount(): void
    {
        $this->products = products::where('is_visible', 1)->get();
        $this->sizes = sizes::select('id', 'size')->get();
        $this->colors = colors::select('id', 'color')->orderBy('color')->get();
        $this->categories = categories::select('id', 'category')->orderBy('category')->get();
    }

    public function render(): View
    {
        return view('livewire.shop');
    }
}
