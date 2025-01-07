<?php

namespace App\Filament\Resources\OrdersResource\Pages;

use App\Filament\Resources\OrdersResource;
use App\Models\shipments;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Database\Eloquent\Model;

class EditOrders extends EditRecord
{
    protected static string $resource = OrdersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $subtotal = $record->orderProducts->sum(function ($product): float
        {
            return $product->quantity * $product->unit_price;
        });

        if ($record->isDirty('shipments_id')) {
            $shipmentPrice = shipments::find($record->shipments_id)->price;
            $record->shipment_price = $shipmentPrice;
        }

        $record->subtotal = $subtotal;

        $record->update($data);

        return $record;
    }
}
