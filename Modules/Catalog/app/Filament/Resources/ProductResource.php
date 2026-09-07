<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\CreateProduct;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\EditProduct;
use Modules\Catalog\Filament\Resources\ProductResource\Pages\ListProducts;
use Modules\Catalog\Models\Product;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Catálogo';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('catalog.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('catalog.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->can('catalog.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->can('catalog.delete') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('sku')
                ->label('SKU')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(150),
            Textarea::make('description')
                ->label('Descripción')
                ->rows(3)
                ->columnSpanFull(),
            TextInput::make('unit')
                ->label('Unidad de medida')
                ->default('unidad')
                ->required(),
            TextInput::make('cost_price')
                ->label('Costo')
                ->numeric()
                ->prefix('$')
                ->required(),
            TextInput::make('sale_price')
                ->label('Precio de venta')
                ->numeric()
                ->prefix('$')
                ->required(),
            TextInput::make('reorder_point')
                ->label('Punto de reorden')
                ->numeric()
                ->default(0)
                ->required(),
            Toggle::make('is_active')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->label('SKU')->searchable()->sortable(),
                TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('unit')->label('Unidad'),
                TextColumn::make('cost_price')->label('Costo')->money('USD'),
                TextColumn::make('sale_price')->label('Precio')->money('USD'),
                TextColumn::make('reorder_point')->label('Reorden')->sortable(),
                IconColumn::make('is_active')->label('Activo')->boolean(),
            ])
            ->filters([])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
