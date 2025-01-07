<?php

namespace App\Filament\Widgets;

use App\Models\orders;
use Filament\Widgets\ChartWidget;

use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;

class OrdersChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Ordenes por mes';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    #[Computed]
    protected function getData(): array
    {
        $orders = Cache::remember('orders', 300, function () {
            return Trend::model(orders::class)
                ->between(
                    start: now()->startOfYear(),
                    end: now(),
                )
                ->perMonth()
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Ordenes',
                    'data' => $orders
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
