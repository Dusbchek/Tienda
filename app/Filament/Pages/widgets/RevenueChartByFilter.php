<?php

namespace App\Filament\Pages\Widgets;

use App\Models\orders;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Livewire\Attributes\Computed;

class RevenueChartByFilter extends ChartWidget
{
    protected static ?string $heading = 'Ingresos';

    protected static bool $isLazy = false;

    public ?string $filter = 'this_year';

    #[Computed()]
    protected function getData(): array
    {
        $chartData = Trend::model(orders::class);

        $betweenDates = function (Carbon $startDate, Carbon $endDate) use (&$chartData): void {
            $chartData = $chartData->between(
                start: $startDate,
                end: $endDate,
            );
        };

        $dateFormat = function (string $date): string {
            Carbon::setLocale('es');
            $formatedDate = Carbon::parse($date);

            $formatedDate = match (strlen($date)) {
                4 => $formatedDate->translatedFormat('Y'), // Año
                7 => $formatedDate->translatedFormat('M'), // Mes
                10 => $formatedDate->translatedFormat('D j F'), // Año, mes y día
                16 => $formatedDate->translatedFormat('H:i'), // Año, mes, día y hora
                default => 'Formato de fecha no válido',
            };

            return ucfirst($formatedDate);
        };

        switch ($this->filter) {
            case 'today':
                $betweenDates(now()->startOfDay(), now());
                break;
            case 'this_week':
                $betweenDates(now()->startOfWeek(), now());
                break;
            case 'last_week':
                $betweenDates(now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek());
                break;
            case 'this_month':
                $betweenDates(now()->startOfMonth(), now());
                break;
            case 'last_month':
                $betweenDates(now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth());
                break;
            case 'this_year':
            case 'per_year':
                $betweenDates(now()->startOfYear(), now());
                break;
        };

        switch ($this->filter) {
            case 'today':
                $chartData = $chartData->perHour();
                break;
            case 'this_week':
            case 'last_week':
            case 'this_month':
            case 'last_month':
                $chartData = $chartData->perDay();
                break;
            case 'this_year':
                $chartData = $chartData->perMonth();
                break;
            case 'per_year':
                $chartData = $chartData->perYear();
                break;
        };

        $chartData = $chartData->sum('subtotal');

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $chartData->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $chartData->map(fn (TrendValue $value) => $dateFormat($value->date)),
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
            'per_year' => 'Por año',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
