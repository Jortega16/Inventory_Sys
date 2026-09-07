<?php

declare(strict_types=1);

namespace Modules\Warehouse\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Warehouse\Filament\Resources\WarehouseResource\Pages\CreateWarehouse;
use Modules\Warehouse\Filament\Resources\WarehouseResource\Pages\EditWarehouse;
use Modules\Warehouse\Filament\Resources\WarehouseResource\Pages\ListWarehouses;
use Modules\Warehouse\Models\Warehouse;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Almacenes';

    protected static ?string $navigationLabel = 'Almacenes';

    protected static ?string $modelLabel = 'almacén';

    protected static ?string $pluralModelLabel = 'almacenes';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('warehouse.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('warehouse.create') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('warehouse.update') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('warehouse.delete') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('code')
                ->label('Código')
                ->required()
                ->maxLength(20)
                ->unique(ignoreRecord: true),
            TextInput::make('name')
                ->label('Nombre')
                ->required()
                ->maxLength(150),
            TextInput::make('address')
                ->label('Dirección')
                ->maxLength(255),
            Toggle::make('is_active')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Código')->searchable()->sortable(),
                TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('address')->label('Dirección'),
                IconColumn::make('is_active')->label('Activo')->boolean(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWarehouses::route('/'),
            'create' => CreateWarehouse::route('/create'),
            'edit' => EditWarehouse::route('/{record}/edit'),
        ];
    }
}
