<?php

namespace App\Filament\Pages\Widgets;

use App\Models\orders;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\Computed;

class HighestDemandStatesChart extends ChartWidget
{
    protected static ?string $heading = 'Estados con mayor demanda';

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

        $orders = orders::whereBetween('created_at', [$startDate, $endDate])->get();

        $states = $orders->groupBy('receiver_state')->map(function ($row) {
            return $row->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Estados',
                    'data' => $states->values(),
                    'borderColor' => '#667ec3'
                ],
            ],
            'labels' => $states->keys(),
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
