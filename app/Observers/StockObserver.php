<?php

namespace App\Observers;

use App\Models\stock;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Filament\Resources\ProductsResource;

class StockObserver
{
    /**
     * Handle the stock "created" event.
     */
    public function created(stock $stock): void
    {
        //
    }

    /**
     * Handle the stock "updated" event.
     */
    public function updated(stock $stock): void
    {
        $pluralStock = $stock->stock != 1 ? 'n' : '';
        $pluralPieza = $stock->stock != 1 ? 's' : '';
        $user = auth()->user();

        if ($stock->isDirty('stock') && $stock->stock < $stock->min_stock) {
            Notification::make()
            ->title("¡El producto '".$stock->products->name."' se está acabando!")
            ->body(
                "Al producto con la talla '". $stock->sizes->size ."' 
                y el color '". $stock->colors->color ."' 
                le queda". $pluralStock ." $stock->stock pieza". $pluralPieza .". 
                La cantidad segura es de: $stock->min_stock piezas"
                )
            ->warning()
            ->icon('heroicon-o-exclamation-triangle')
            ->actions([
                Action::make('Ver')
                    ->url(ProductsResource::getUrl('edit', ['record' => $stock->products, 'activeRelationManager' => 2]))
                    ->openUrlInNewTab()
                    ->markAsRead(),
            ])
            ->sendToDatabase($user);
        }
    }

    /**
     * Handle the stock "deleted" event.
     */
    public function deleted(stock $stock): void
    {
        //
    }

    /**
     * Handle the stock "restored" event.
     */
    public function restored(stock $stock): void
    {
        //
    }

    /**
     * Handle the stock "force deleted" event.
     */
    public function forceDeleted(stock $stock): void
    {
        //
    }
}
