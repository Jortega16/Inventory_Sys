<?php

declare(strict_types=1);

namespace Modules\Warehouse\Filament\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Catalog\Models\Product;
use Modules\Warehouse\Filament\Resources\StockTransferResource\Pages\CreateStockTransfer;
use Modules\Warehouse\Filament\Resources\StockTransferResource\Pages\ListStockTransfers;
use Modules\Warehouse\Models\StockTransfer;
use Modules\Warehouse\Models\Warehouse;

class StockTransferResource extends Resource
{
    protected static ?string $model = StockTransfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Almacenes';

    protected static ?string $navigationLabel = 'Transferencias';

    protected static ?string $modelLabel = 'transferencia';

    protected static ?string $pluralModelLabel = 'transferencias';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('warehouse.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('warehouse.transfer') ?? false;
    }

    // Las transferencias son un movimiento histórico: no se editan ni se borran.
    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Producto')
                ->options(fn () => Product::query()->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Select::make('from_warehouse_id')
                ->label('Almacén origen')
                ->options(fn () => Warehouse::query()->pluck('name', 'id'))
                ->required()
                ->live(),
            Select::make('to_warehouse_id')
                ->label('Almacén destino')
                ->options(fn () => Warehouse::query()->pluck('name', 'id'))
                ->required()
                ->rule('different:from_warehouse_id')
                ->validationMessages([
                    'different' => 'El almacén destino debe ser distinto al de origen.',
                ]),
            TextInput::make('quantity')
                ->label('Cantidad')
                ->numeric()
                ->minValue(1)
                ->required(),
            TextInput::make('note')
                ->label('Nota')
                ->maxLength(255),
            DateTimePicker::make('transferred_at')
                ->label('Fecha')
                ->default(now())
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Producto')->searchable(),
                TextColumn::make('fromWarehouse.name')->label('Origen'),
                TextColumn::make('toWarehouse.name')->label('Destino'),
                TextColumn::make('quantity')->label('Cantidad')->numeric(),
                TextColumn::make('transferred_at')->label('Fecha')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->defaultSort('transferred_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockTransfers::route('/'),
            'create' => CreateStockTransfer::route('/create'),
        ];
    }
}
