<?php

namespace App\Observers;

use App\Filament\Resources\OrdersResource;
use App\Models\orders;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class OrdersObserver
{
    /**
     * Handle the orders "created" event.
     */
    public function created(orders $orders): void
    {
        /* $recipient = auth()->user();

        Notification::make()
            ->title('¡Hay una nueva orden!')
            ->body("¡$orders->receiver_name ha hecho una orden!")
            ->icon('heroicon-o-shopping-bag')
            ->info()
            ->actions([
                Action::make('Ver')
                    ->url(OrdersResource::getUrl('edit', ['record' => $orders]))
                    ->openUrlInNewTab()
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipient); */
    }

    /**
     * Handle the orders "updated" event.
     */
    public function updated(orders $order): void
    {
        /* $recipient = auth()->user();

        Notification::make()
            ->title('¡Se ha mofificado una orden!')
            ->body("¡$order->receiver_name ha modificado la orden: <a class='text-blue-important' href='".OrdersResource::getUrl('edit', ['record' => $order])."'>$order->number</a>!")
            ->info()
            ->actions([
                Action::make('Ver')
                    ->url(OrdersResource::getUrl('edit', ['record' => $order]))
                    ->openUrlInNewTab()
                    ->markAsRead(),
            ])
            ->sendToDatabase($recipient); */
    }

    /**
     * Handle the orders "deleted" event.
     */
    public function deleted(orders $orders): void
    {
        //
    }

    /**
     * Handle the orders "restored" event.
     */
    public function restored(orders $orders): void
    {
        //
    }

    /**
     * Handle the orders "force deleted" event.
     */
    public function forceDeleted(orders $orders): void
    {
        //
    }
}
