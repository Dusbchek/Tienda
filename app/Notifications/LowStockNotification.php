<?php

namespace App\Notifications;

use App\Filament\Resources\ProductsResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\stock;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;

class LowStockNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase(User $notifiable, stock $stock)
    {
        $pluralStock = $stock->stock != 1 ? 'n' : '';
        $pluralPieza = $stock->stock != 1 ? 's' : '';

        return FilamentNotification::make()
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
            ->sendToDatabase($notifiable);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
