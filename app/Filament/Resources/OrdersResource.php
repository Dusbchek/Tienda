<?php

namespace App\Filament\Resources;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;

use Filament\Resources\Resource;
use App\Filament\Resources\OrdersResource\Pages;
use App\Filament\Resources\OrdersResource\Widgets\OrderStats;

use App\Models\Orders;
use App\Models\products;
use App\Enums\OrderStatus;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;

use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;

class OrdersResource extends Resource
{

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $model = Orders::class;

    protected static ?string $modelLabel = "Orden";
    protected static ?string $pluralModelLabel = "Ordenes";

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = "Pedidos";

    public static function buildOrderNumber(): string
    {
        $orderId = str_pad((Orders::all()->count() + 1), 3, '0', STR_PAD_LEFT);

        // Obtiene la fecha actual en formato ddmmaaaa
        $date = Carbon::now()->format('dmy');

        // Genera un número aleatorio de 1 a 999 y formatea el resultado
        $items = str_pad(random_int(100, 999), 3, '0', STR_PAD_LEFT);

        // Construye el número de orden
        return "OR-{$orderId}-01-{$date}-{$items}";
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema(
                                static::getOrderDetails(),
                                )
                            ->columns(1),
                    ])
                    ->columnSpan(['lg' => fn (?Orders $record) => $record === null ? 3 : 2]),

                Group::make()
                    ->schema([

                        Section::make('Datos del destinatario')
                            ->schema([
                                Placeholder::make('receiver_name')
                                ->label('Nombre:')
                                ->content(fn (Orders $record): ?string => $record->receiver_name),

                                Placeholder::make('receiver_phone')
                                ->label('Teléfono:')
                                ->content(fn (Orders $record): ?string => ($record->receiver_phone) ? $record->receiver_phone : "No especificado"),

                                Placeholder::make('receiver_email')
                                ->label('Correo electrónico:')
                                ->content(fn (Orders $record): ?string => ($record->receiver_email) ? $record->receiver_email : "No especificado"),
                        ])
                        ->columnSpan(['lg' => 1])
                        ->hidden(fn (?Orders $record) => $record === null),

                        Section::make()
                            ->schema([
                                Placeholder::make('created_at')
                                ->label('Creado:')
                                ->content(fn (Orders $record): ?string => $record->created_at?->diffForHumans()),

                                Placeholder::make('updated_at')
                                    ->label('Última modificación:')
                                    ->content(fn (Orders $record): ?string => $record->updated_at?->diffForHumans()),
                        ])
                        ->columnSpan(['lg' => 1])
                        ->hidden(fn (?Orders $record) => $record === null),
                    ]),

                Section::make('Productos de la orden')
                    ->headerActions([
                        Action::make('Borrar Todo')
                            ->action(fn (Set $set) => $set('orderProducts', []))
                            ->requiresConfirmation()
                            ->modalHeading('¡Cuidado!')
                            ->modalDescription('Todos los productos existentes se eliminarán de la orden.')
                            ->color('danger'),
                    ])
                    ->schema([
                        static::getItemsRepeater(),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Número de orden')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Estado de la orden')
                    ->badge()
                    ->sortable()
                    ->toggleable(), 

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Sum::make()
                            ->label('Ventas totales')
                            ->money(),
                    ]),

                TextColumn::make('shipment_price')
                    ->label('Costo de envío')
                    ->money()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Sum::make()
                            /* ->query(fn (Builder $query) => $query->where('is_published', true)) */
                            ->label('Gastos de envío')
                            ->money(),
                    ]),

                TextColumn::make('created_at')
                    ->label('Fecha de pedido')
                    ->date()
                    ->sortable()
                    ->toggleable()
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Fecha de inicio')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        DatePicker::make('created_until')
                            ->label('Fecha de fin')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (EloquentBuilder $query, array $data): EloquentBuilder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (EloquentBuilder $query, $date): EloquentBuilder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (EloquentBuilder $query, $date): EloquentBuilder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['number',];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('status', 'Nueva')->count();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrders::route('/create'),
            'edit' => Pages\EditOrders::route('/{record}/edit'),
        ];
    }

    public static function getOrderDetails(): Array
    {
        return [
            TextInput::make('number')
                ->label('Número de orden')
                ->default(static::buildOrderNumber())
                ->readOnly()
                ->required(),

            ToggleButtons::make('status')
                ->label('Estado de la orden')
                ->inline()
                ->options(OrderStatus::class)
                ->default(OrderStatus::New)
                ->validationMessages([
                    'required' => 'El :attribute es necesario.',
                ])
                ->required(),

            Select::make('shipments_id')
                ->label('Tipo de envío')
                ->relationship('shipments', 'type')
                ->preload()
                ->live()
                ->validationMessages([
                    'required' => 'El :attribute es necesario.'
                ])
                ->required(),

            TextInput::make('receiver_street')
                ->label('Dirección postal')
                ->maxLength(255)
                ->columnSpanFull()
                ->validationMessages([
                    'required' => 'La :attribute es necesaria.'
                ])
                ->required(),

            Grid::make([
                    'sm' => 3,
                ])
                ->schema([
                    TextInput::make('receiver_city')

                        ->label('Ciudad')
                        ->maxLength(255)
                        ->validationMessages([
                            'required' => 'La :attribute es necesaria.'
                        ])
                        ->required(),

                    Select::make('receiver_state')
                        ->label('Estado/Provincia')
                        ->options([
                            'aguascalientes' => 'Aguascalientes',
                            'baja california' => 'Baja California',
                            'baja california sur' => 'Baja California Sur',
                            'campeche' => 'Campeche',
                            'chiapas' => 'Chiapas',
                            'chihuahua' => 'Chihuahua',
                            'ciudad de mexico' => 'Ciudad de México',
                            'coahuila de zaragoza' => 'Coahuila de Zaragoza',
                            'colima' => 'Colima',
                            'durango' => 'Durango',
                            'estado de mexico' => 'Estado de México',
                            'guanajuato' => 'Guanajuato',
                            'guerrero' => 'Guerrero',
                            'hidalgo' => 'Hidalgo',
                            'jalisco' => 'Jalisco',
                            'michoacan de ocampo' => 'Michoacán de Ocampo',
                            'morelos' => 'Morelos',
                            'nayarit' => 'Nayarit',
                            'nuevo leon' => 'Nuevo León',
                            'oaxaca' => 'Oaxaca',
                            'puebla' => 'Puebla',
                            'queretaro' => 'Querétaro',
                            'quintana roo' => 'Quintana Roo',
                            'san luis potosi' => 'San Luis Potosí',
                            'sinaloa' => 'Sinaloa',
                            'sonora' => 'Sonora',
                            'tabasco' => 'Tabasco',
                            'tamaulipas' => 'Tamaulipas',
                            'tlaxcala' => 'Tlaxcala',
                            'veracruz' => 'Veracruz',
                            'yucatan' => 'Yucatán',
                            'zacatecas' => 'Zacatecas',
                        ])
                        ->validationMessages([
                            'required' => 'El :attribute es necesario.'
                        ])
                        ->required(),

                    TextInput::make('receiver_zip')

                        ->label('Código Postal')
                        ->maxLength(255)
                        ->validationMessages([
                            'required' => 'El :attribute es necesario.'
                        ])
                        ->required(),
                ]),

            Textarea::make('receiver_reference')
                ->label('Referencias')
                ->rows(5)
                ->columnSpanFull(),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('orderProducts')
        ->relationship()
        ->schema([
            Select::make('product_name')
            ->label('Producto')
            ->searchable()
            ->options(products::where('is_visible', 1)->pluck('name', 'name'))
            ->afterStateUpdated(
                function($state, Set $set){
                    $set('unit_price', products::firstWhere('name', $state)?->price ?? 0);
                    $set('categories', products::firstWhere('name', $state)?->categories()->pluck('category') ?? '');
                },
            )
            ->live()
            ->validationMessages([
                'required' => 'Selecciona un :attribute.'
            ])
            ->required(),

            Select::make('size')
                ->label('Talla')
                ->options(function(Get $get): Collection
                    {
                        $product = products::firstWhere('name', $get('product_name'));
                        if(!$product) return collect([]);
                        return $product->sizes()->pluck('size', 'size');
                    })
                ->validationMessages([
                    'required' => 'Selecciona un :attribute.'
                ])
                ->required(),

            Select::make('color')
                ->label('Color')
                ->options(function(Get $get): Collection
                    {
                        $product = products::firstWhere('name', $get('product_name'));
                        if(!$product) return collect([]);
                        return $product->colors()->pluck('color', 'color');
                    })
                ->validationMessages([
                    'required' => 'Selecciona un :attribute.'
                ])
                ->required(),

            TextInput::make('quantity')
                ->label('Cantidad')
                ->numeric()
                ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('subtotal_price', $get('unit_price') * $state))
                ->live()
                ->required()
                ->validationMessages([
                    'required' => 'Selecciona la cantidad del producto.'
                ]),

            TextInput::make('unit_price')
                ->label('Precio unitario')
                ->numeric()
                ->readOnly(),

            TextInput::make('subtotal_price')
                ->label('Subtotal')
                ->numeric()
                ->readOnly(),

            Hidden::make('categories'),
        ])
        ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
            $data['subtotal_price'] = $data['quantity'] * $data['unit_price'];
            return $data;
        })
        ->hiddenLabel()
        ->collapsible()
        ->cloneable()
        ->reorderable()
        ->reorderableWithButtons()
        ->addActionLabel('Añadir producto')
        ->columns(['sm' => 2, 'lg' => 3]);
    }
}
