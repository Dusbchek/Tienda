<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Widgets\OrdersChartByFilter;
use App\Filament\Pages\Widgets\RevenueChartByFilter;
use App\Filament\Pages\Widgets\BestSellingCategoriesCartByFilter;
use App\Filament\Pages\Widgets\HighestDemandStatesChart;
use App\Models\orders;
use Filament\Pages\Page;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;

class FinancialInformation extends Page
{
    use HasFiltersAction;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static string $view = 'filament.pages.financial-information';

    protected static ?string $model = orders::class;

    protected static ?string $title = 'Estadísticas';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = "Administración";

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrdersChartByFilter::class,
            RevenueChartByFilter::class,
            BestSellingCategoriesCartByFilter::class,
            HighestDemandStatesChart::class
        ];
    }
}
