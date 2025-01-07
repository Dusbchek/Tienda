<?php

namespace App\Filament\Resources\ProductsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\CreateAction;

class ColorProductRelationManager extends RelationManager
{
    protected static string $relationship = 'colors';

    protected static ?string $title = 'Colores';
    protected static ?string $modelLabel = "Color";
    protected static ?string $pluralModelLabel  = "Colores";

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make("colors_id")
                    ->relationship("colors", "color")
                    ->label("Color")
                    ->createOptionForm([
                        TextInput::make("color")
                            ->required()
                            ->maxLength(255),

                        ColorPicker::make('hexadecimal')
                            ->required()
                    ])
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Colores')
            ->columns([
                TextColumn::make('colors.color')
                    ->label("Color"),

                ColorColumn::make('colors.hexadecimal')
                    ->copyable()
                    ->label("Vista")

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label("Añadir Color"),
            ])
            ->actions([
                EditAction::make()
                    ->label("Editar color"),
                DeleteAction::make()
                    ->label("Eliminar color"),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label("Eliminar colores"),
                ]),

            ]);
    }
}
