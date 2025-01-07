<?php

namespace App\Filament\Resources;

use Filament\Support\RawJs;
use Filament\Resources\Resource;
use App\Filament\Resources\ProductsResource\Pages;
use App\Filament\Resources\ProductsResource\RelationManagers;
use App\Filament\Resources\ProductsResource\Widgets\ProductStats;
use App\Models\Products;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Tables;
use Filament\Tables\Table;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

use Filament\Forms\Set;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Toggle;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $modelLabel = "Producto";
    protected static ?string $PluralModelLabel = "Productos";

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationGroup = "Tienda";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make()
                    ->schema([
                        Section::make('Datos generales')
                            ->schema([
                                TextInput::make("name")
                                    ->label('Nombre')
                                    ->maxLength(255)
                                    ->Live(onBlur: true)
                                    ->afterStateUpdated(fn( Set $set, ?string $state ) => $set('slug', Str::slug($state)))
                                    ->validationMessages([
                                        'required' => 'El :attribute es necesario.'
                                    ])
                                    ->required(),

                                TextInput::make('slug')
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->maxLength(255),

                                TextInput::make("price")
                                    ->label('Precio')
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->numeric()
                                    ->inputMode('decimal')
                                    ->minValue(1)
                                    ->maxLength(255)
                                    ->validationMessages([
                                        'min' => 'El precio debe ser mayor a 0',
                                        'required' => 'El :attribute es necesario.'
                                    ])
                                    ->required(),

                                MarkdownEditor::make('description')
                                    ->label('Descripción')
                                    ->toolbarButtons([
                                        'redo',
                                        'undo',
                                    ])
                                    ->columnSpan('full')
                                    ->validationMessages([
                                        'required' => 'La :attribute es necesaria.'
                                    ])
                                    ->required(),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make('Características del producto')
                            ->schema([
                                Toggle::make('is_visible')
                                    ->label('Visible')
                                    ->helperText('Marca la visibilidad del producto para los clientes.')
                                    ->default(true),

                                    Select::make("sizes")
                                        ->label('Tallas')
                                        ->multiple()
                                        ->searchable()
                                        ->relationship(name: "sizes",
                                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('id'))
                                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->size}")
                                        ->loadingMessage('Cargando tallas...')
                                        ->preload()
                                        ->createOptionForm([
                                            TextInput::make("size")
                                                ->label('Talla')
                                                ->maxLength(255)
                                                ->validationMessages([
                                                    'required' => 'La :attribute es necesaria.'
                                                ])
                                                ->required(),
                                        ])
                                        ->validationMessages([
                                            'required' => 'Es necesario seleccionar al menos una talla.'
                                        ])
                                        ->required(),

                                    Select::make("categories")
                                        ->label('Categorías')
                                        ->multiple()
                                        ->searchable()
                                        ->relationship(name: "categories",
                                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('category'))
                                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->category}")
                                        ->loadingMessage('Cargando categorías...')
                                        ->preload()
                                        ->createOptionForm([
                                            TextInput::make("category")
                                                ->label('Categoría')
                                                ->maxLength(255)
                                                ->validationMessages([
                                                    'required' => 'La :attribute es necesaria.'
                                                ])
                                                ->required(),
                                        ])
                                        ->validationMessages([
                                            'required' => 'Es necesario seleccionar al menos una categoría.'
                                        ])
                                        ->required(),

                                Select::make('color')
                                    ->multiple()
                                    ->label('Colores del producto')
                                    ->searchable()
                                    ->relationship("colors", "color")
                                    ->loadingMessage('Cargando colores...')
                                    ->preload()
                                    ->validationMessages([
                                        'required' => 'Es necesario seleccionar al menos un color.'
                                    ])
                                    ->required(),
                            ])
                            ->columns(1),
                    ])
                    ->columnSpan(['sm' => 2, 'xl' => 1]),

            ])
            ->columns(["sm" => 2, "xl" => 3]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Section::make("Datos generales")
                    ->schema([
                        TextEntry::make('name')->label("Nombre")
                            ->columnspan("1.5"),

                        TextEntry::make('price')->label("Precio")
                            ->numeric(decimalPlaces: 2)
                            ->money('MXN'),

                        TextEntry::make('sizes.size')->label("Tallas")
                            ->badge()
                            ->columns("4"),

                        TextEntry::make('categories.category')
                            ->label("Categorias")
                            ->badge(),

                        TextEntry::make('description')->label("Descripción")
                            ->columnspan("2"),


                    ])->columns(4),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('price')
                    ->label('Precio')
                    ->searchable()
                    ->money('MXN')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('sizes.size')
                    ->label('Tallas')
                    ->toggleable(),

                TextColumn::make('categories.category')
                    ->searchable()
                    ->label('Categorias')
                    ->toggleable(),

                IconColumn::make('is_visible')
                    ->label('Visibilidad')
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
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Eliminar'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Eliminar'),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }


    public static function getRelations(): array
    {
        return [

            RelationManagers\ImagesRelationManager::class,
            RelationManagers\MeasuresRelationManager::class,
            RelationManagers\StockRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
        ];
    }
}
