<?php

namespace App\Filament\Resources\ProductsResource\RelationManagers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

use App\Models\images;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\CreateAction;

class ImagesRelationManager extends RelationManager
{
    //Escucha el evento de la edición del producto para refrescar la relación
    protected $listeners = ['refreshRelation' => 'refresh'];

    protected static string $relationship = 'images';

    protected static ?string $title = 'Imágenes';
    protected static ?string $modelLabel = "Imagen";
    protected static ?string $pluralModelLabel = 'Imágenes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make("image")
                    ->label('Imagen')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '9:16',
                        '3:4',
                        '1:1',
                    ])
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('900')
                    ->imageResizeTargetHeight('1280')
                    ->required(),

                Select::make("colors_id")
                    ->label('Color')
                    ->createOptionForm([
                        TextInput::make("color")
                            ->required()
                            ->maxLength(255),

                        ColorPicker::make('hexadecimal')
                            ->required()
                    ])
                    ->options(function (RelationManager $livewire) {
                        $productColors = $livewire->ownerRecord->colors()->pluck('color', 'colors.id');
                        return $productColors;
                    })
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagenes')
                    ->square(),

                TextColumn::make('colors.color')
                    ->label('Color'),

                ColorColumn::make('colors.hexadecimal')
                    ->label('Vista'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Añadir Imagen'),
            ])
            ->actions([
                EditAction::make()
                    ->label('Editar'),
                DeleteAction::make()
                    ->label('Eliminar')
                    /**
                     * Antes de hacer la elimincación del registro,
                     * obtiene la imagen relacionada al registro y la elimina del almacenamiento.
                     */
                    ->before(function (images $resource) {
                        Storage::delete([$resource->image]);
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Eliminar')
                        ->before(function (Collection $records) {
                            // Recorre cada registro
                            foreach ($records as $record) {
                                // Elimina la imagen del almacenamiento
                                Storage::delete([$record->image]);
                            }
                        }),
                ]),
            ]);
    }
}
