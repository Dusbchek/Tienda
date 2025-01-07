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

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\CreateAction;

class MeasuresRelationManager extends RelationManager
{
    //Escucha el evento de la edición del producto para refrescar la relación
    protected $listeners = ['refreshRelation' => 'refresh'];

    protected static string $relationship = 'Measures';

    protected static ?string $title = 'Medidas';
    protected static ?string $modelLabel = "Medida";
    protected static ?string $pluralModelLabel  = "Medidas";

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('part')
                    ->required()
                    ->label("Parte")
                    ->maxLength(255),

                Select::make("sizes_id")
                    ->label("Talla")
                    ->options(function (RelationManager $livewire) {
                        $productSizes = $livewire->ownerRecord->sizes()->pluck('size', 'sizes.id');
                        return $productSizes;
                    })
                    ->searchable()
                    ->preload(),

                TextInput::make('measure')
                    ->label("Medida")
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sizes.size')
                    ->label("Talla"),

                TextColumn::make('part')
                    ->label("Parte"),

                TextColumn::make('measure')
                    ->label("Medida en cm"),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label("Añadir Medida"),
            ])
            ->actions([
                EditAction::make()
                    ->label("Editar"),
                DeleteAction::make()
                    ->label("Eliminar"),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label("Eliminar medidas"),
                ]),
            ]);
    }
}
