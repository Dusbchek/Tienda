<?php

namespace App\Filament\Pages\Widgets;

use App\Models\orderProducts;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\Computed;

class BestSellingCategoriesCartByFilter extends ChartWidget
{
    protected static ?string $heading = 'Categorías más vendidas';

    protected static bool $isLazy = false;

    public ?string $filter = 'this_year';

    #[Computed()]
    protected function getData(): array
    {
        $startDate = now();
        $endDate = now();

        switch ($this->filter) {
            case 'today':
                $startDate = now()->startOfDay();
                break;
            case 'this_week':
                $startDate = now()->startOfWeek();
                break;
            case 'last_week':
                $startDate = now()->subWeek()->startOfWeek();
                $endDate = now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = now()->startOfMonth();
                break;
            case 'last_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $startDate = now()->startOfYear();
                break;
        };

        // Obtiene todas las ventas en el rango de fechas
        $sales = orderProducts::whereBetween('created_at', [$startDate, $endDate])->get();

        // Cuenta las ventas por categoría
        $salesCount = $sales->flatMap(fn ($orderProduct) => json_decode($orderProduct->categories, true))
            ->countBy();

        // Obtiene el nombre de las categorías
        $categories = $salesCount->keys();

        return [
            'datasets' => [
                [
                    'label' => 'Categorías',
                    'data' => $salesCount->values(),
                    'borderColor' => '#667ec3',
                ],
            ],
            'labels' => $categories->values(),
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hoy',
            'this_week' => 'Esta semana',
            'last_week' => 'La semana pasada',
            'this_month' => 'Este mes',
            'last_month' => 'El mes pasado',
            'this_year' => 'Este año',
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
