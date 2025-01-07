<?php

namespace App\Filament\Resources\OrdersResource\Widgets;

use app\Filament\Resources\OrdersResource\Pages\ListOrders;
use App\Models\orders;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Livewire\Attributes\Computed;

class OrderStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    #[Computed]
    protected function getStats(): array
    {
        $orderData = Trend::model(orders::class)
        ->between(
            start: now()->startOfYear(),
            end: now(),
        )
        ->perMonth()
        ->count();

        return [
            Stat::make('Ordenes', $this->getPageTableQuery()->count())
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('Ordenes Abiertas', $this->getPageTableQuery()->whereIn('status', ['Nueva', 'En Proceso', 'Enviada'])->count()),
            Stat::make('Ticket Promedio', number_format($this->getPageTableQuery()->avg('subtotal'), 2))
            ->description('Promedio de venta por orden'),
        ];
    }
}
