<?php

namespace App\Filament\Resources\OrdersResource\Pages;

use App\Filament\Resources\OrdersResource;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;

use Filament\Resources\Pages\CreateRecord;

class CreateOrders extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrdersResource::class;

    protected function afterCreate(): void
    {
        $order = $this->record;
        $subtotal = $order->orderProducts->sum(function ($product): float
        {
            return $product->quantity * $product->unit_price;
        });
        $shipment_price = $order->shipments->price;

        $order->subtotal = $subtotal;
        $order->shipment_price = $shipment_price;
        $order->save();
    }

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    /** @return Step[] */
    protected function getSteps(): array
    {
        return [
            Step::make('Datos de envío')
                ->icon('heroicon-m-truck')
                ->schema([
                    Section::make()->schema(OrdersResource::getOrderDetails())
                    ->columnSpan(2),

                    Section::make('Datos del destinatario')
                    ->schema([
                        TextInput::make('receiver_name')
                            ->label('Nombre')
                            ->autocapitalize('words')
                            ->validationMessages([
                                'required' => 'El :attribute es necesario.'
                            ])
                            ->required(),

                        TextInput::make('receiver_phone')
                            ->label('Teléfono')
                            ->minLength(10)
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                            ->validationMessages([
                                'regex' => 'Ingresa un número de teléfono válido.',
                                'min' => 'El número de teléfono debe tener al menos 10 dígitos',
                            ])
                            ->validationMessages([
                                'required' => 'El :attribute es necesario.'
                            ])
                            ->required(),

                        TextInput::make('receiver_email')
                            ->label('Correo electrónico')
                            ->email(),
                    ])
                    ->columns(['sm' => 3, 'xl' => 1])
                    ->columnSpan(['sm' => 2, 'xl' => 1]),
                ])
                ->columns(["sm" => 2, "xl" => 3]),

            Step::make('Productos de la orden')
                ->icon('heroicon-m-shopping-bag')
                ->schema([
                    Section::make()
                    ->schema([
                        OrdersResource::getItemsRepeater(),
                    ]),
                ]),
        ];
    }
}
