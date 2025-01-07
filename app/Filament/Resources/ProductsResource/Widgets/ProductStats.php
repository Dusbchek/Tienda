<?php

namespace App\Filament\Resources\ProductsResource\Widgets;

use App\Filament\Resources\ProductsResource\Pages\ListProducts;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\Computed;

class ProductStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListProducts::class;
    }

    #[Computed]
    protected function getStats(): array
    {
        return [
            Stat::make('Total de productos', $this->getPageTableQuery()->count()),
            Stat::make('Productos publicados', $this->getPageTableQuery()->where('is_visible', true)->count()),
            Stat::make('Precio promedio', number_format($this->getPageTableQuery()->avg('price'), 2)),
        ];
    }
}
