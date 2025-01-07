<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;

class CustomersChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Crecimiento de clientes por mes';

    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    #[Computed]
    protected function getData(): array
    {
        $data = Cache::remember('customerData', 300, function(){
            return [4344, 5676, 6798, 7890, 8987, 9388, 10343];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Customers',
                    'data' => $data,
                    'fill' => 'start',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
