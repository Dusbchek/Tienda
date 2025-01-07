<?php

namespace App\Filament\Widgets;

use App\Models\orders;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Number;
use Livewire\Attributes\Computed;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static bool $isLazy = false;

    #[Computed]
    protected function getStats(): array
    {
        $formatNumber = function (int $number): string {
            return $number = match (true) {
                $number < 1000 =>       (string) Number::format($number, 0),
                $number < 1000000 =>    (string) Number::format($number / 1000, 2) . 'k',
                default =>              (string) Number::format($number / 1000000, 2) . 'm'
            };
        };

        $calculatePercentageChange = function($actual, $previous): float {
            if ($previous > 0) {
                return round((($actual - $previous) / $previous) * 100, 2);
            }
            return 0;
        };

        $getChangeDescription = function($change): string {
            return "$change% " . ($change >= 0 ? "más" : "menos") . " que el mes pasado";
        };

        $getChangeIcon = function($change): string {
            return $change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        };

        $getChangeColor = function($change): string {
            return $change >= 0 ? 'success' : 'danger';
        };

        $getPrevData = function(string $model, string $method, string $column = null) {
            $query = $model::whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()]);
            return $column ? $query->$method($column) : $query->$method();
        };
        $getActualData = function(string $model, string $method, string $column = null) {
            $query = $model::whereBetween('created_at', [now()->startOfMonth(), now()]);
            return $column ? $query->$method($column) : $query->$method();
        };

        $revenue = Trend::model(orders::class)
            ->between(
                start: now()->startOfYear(),
                end: now(),
            )
            ->perMonth()
            ->sum('subtotal')
            ->map(fn (TrendValue $value) => $value->aggregate)
            ->toArray();
        $previousRevenue = $getPrevData(orders::class, 'sum', 'subtotal');
        $actualRevenue = $getActualData(orders::class, 'sum', 'subtotal');
        $profit = $calculatePercentageChange($actualRevenue, $previousRevenue);


        $totalOrders = Trend::model(orders::class)
            ->between(
                start: now()->startOfYear(),
                end: now(),
            )
            ->perMonth()
            ->count()
            ->map(fn (TrendValue $value) => $value->aggregate)
            ->toArray();
        $previousOrders = $getPrevData(orders::class, 'count');
        $actualOrders = $getActualData(orders::class, 'count');
        $orders = $calculatePercentageChange($actualOrders, $previousOrders);

        return [
            Stat::make('Ganancias', '$' . $formatNumber($actualRevenue))
                ->description($getChangeDescription($profit))
                ->descriptionIcon($getChangeIcon($profit))
                ->chart($revenue)
                ->color($getChangeColor($profit)),
            Stat::make('Total de clientes', 1/* $formatNumber($newCustomers) */)
                ->description('3% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([17, 16, 14, 15, 14, 13, 12])
                ->color('danger'),
            Stat::make('Ordenes nuevas', $formatNumber($actualOrders))
                ->description($getChangeDescription($orders))
                ->descriptionIcon($getChangeIcon($orders))
                ->chart($totalOrders)
                ->color($getChangeColor($orders)),
        ];
    }
}
