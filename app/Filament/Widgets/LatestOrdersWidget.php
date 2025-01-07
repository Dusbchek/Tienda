<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrdersResource;
use App\Models\orders;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected static bool $isLazy = true;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrdersResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha de pedido')
                    ->date()
                    ->sortable(),
                TextColumn::make('number')
                    ->label('Número de orden')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('receiver_name')
                    ->label('Destinatario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado de la orden')
                    ->badge(),
                TextColumn::make('subtotal')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('shipment_price')
                    ->label('Costo de envío')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Action::make('open')
                    ->label('Ver')
                    ->url(fn (orders $record): string => OrdersResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
