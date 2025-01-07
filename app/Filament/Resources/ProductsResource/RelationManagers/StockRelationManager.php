<?php

namespace App\Filament\Resources\ProductsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\CreateAction;

class StockRelationManager extends RelationManager
{
    //Escucha el evento de la edición del producto para refrescar la relación
    protected $listeners = ['refreshRelation' => 'refresh'];

    protected static string $relationship = 'stock';

    protected static ?string $title = 'Inventario';
    protected static ?string $modelLabel = "Inventario";
    protected static ?string $pluralModelLabel  = "Inventario";

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sizes_id')
                    ->label('Talla')
                    ->options(function (RelationManager $livewire) {
                        $productSizes = $livewire->ownerRecord->sizes()->pluck('size', 'sizes.id');
                        return $productSizes;
                    })
                    ->required(),

                Select::make('colors_id')
                    ->label('Color')
                    ->options(function (RelationManager $livewire) {
                        $productColors = $livewire->ownerRecord->colors()->pluck('color', 'colors.id');
                        return $productColors;
                    })
                    ->required(),

                TextInput::make('stock')
                    ->label('Cantidad en Stock')
                    ->required()
                    ->numeric()
                    ->maxLength(255),

                TextInput::make('min_stock')
                    ->label('Cantidad segura de stock')
                    ->helperText('La cantidad segura es el límite de sus productos que le avisa si el stock del producto se agotará pronto.')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sizes.size')
                    ->label("Talla"),

                TextColumn::make('colors.color')
                    ->label("Color"),

                ColorColumn::make('colors.hexadecimal')
                    ->label('Vista'),

                TextColumn::make('stock')
                    ->label("Cantidad"),

                TextColumn::make('min_stock')
                    ->label("Cantidad Segura"),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label("Añadir inventario"),
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
}
