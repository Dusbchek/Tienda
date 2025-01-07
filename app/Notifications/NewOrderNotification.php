<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Filament\Resources\OrdersResource;

use App\Models\orders;
use App\Models\User;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;

class NewOrderNotification extends Notification
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

    public function toDatabase(User $notifiable, orders $order)
    {
        $numProds = $order->orderProducts()->count();
        return FilamentNotification::make()
            ->title('¡Hay una nueva orden!')
            ->body("$order->receiver_name ha hecho una orden de $numProds productos")
            ->icon('heroicon-o-shopping-bag')
            ->info()
            ->actions([
                Action::make('Ver')
                    ->url(OrdersResource::getUrl('edit', ['record' => $order]))
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
