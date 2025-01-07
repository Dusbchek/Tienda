<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColorsResource\Pages;
use App\Filament\Resources\ColorsResource\RelationManagers;
use App\Models\Colors;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class ColorsResource extends Resource
{
    protected static ?string $model = Colors::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye-dropper';

    protected static ?string $modelLabel = "Color";
    protected static ?string $pluralModelLabel  = "Colores";

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = "Tienda";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('color')
                        ->validationMessages([
                            'required' => 'El :attribute es necesario.'
                        ])
                        ->required(),

                        ColorPicker::make('hexadecimal')
                        ->validationMessages([
                            'required' => 'El :attribute es necesario.'
                        ])
                        ->required(),
                    ])
                    ->columnSpan(['lg' => 2])
                    ->columns(2),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('color')
                ->sortable()
                ->toggleable(),

                ColorColumn::make('hexadecimal')
                    ->tooltip('Copiar hexadecimal')
                    ->copyable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Última modificación')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()->label('Eliminar'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListColors::route('/'),
            'create' => Pages\CreateColors::route('/create'),
            'edit' => Pages\EditColors::route('/{record}/edit'),
        ];
    }
}
