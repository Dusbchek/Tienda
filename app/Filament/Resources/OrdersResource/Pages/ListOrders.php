<?php

namespace App\Filament\Resources\OrdersResource\Pages;

use App\Filament\Resources\OrdersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = OrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return OrdersResource::getWidgets();
    }

    protected function afterSave(): void
    {
        $order = $this->record;
        $subtotal = $order->orderProducts->sum(function ($product): float
        {
            return $product->quantity * $product->unit_price;
        });

        $order->subtotal = $subtotal;
        $order->save();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todas'),
            'Nuevas'        => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Nueva')),
            'En Proceso'    => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'En Proceso')),
            'Enviadas'      => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Enviada')),
            'Entregadas'    => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Entregada')),
            'Canceladas'    => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Cancelada')),
        ];
    }
}
