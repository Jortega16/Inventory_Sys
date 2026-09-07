<?php

declare(strict_types=1);

namespace Modules\Sales\Filament\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Models\Product;
use Modules\Sales\Filament\Resources\SaleOrderResource\Pages\CreateSaleOrder;
use Modules\Sales\Filament\Resources\SaleOrderResource\Pages\EditSaleOrder;
use Modules\Sales\Filament\Resources\SaleOrderResource\Pages\ListSaleOrders;
use Modules\Sales\Models\Customer;
use Modules\Sales\Models\SaleOrder;
use Modules\Warehouse\Models\Warehouse;

class SaleOrderResource extends Resource
{
    protected static ?string $model = SaleOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Ventas';

    protected static ?string $navigationLabel = 'Órdenes de venta';

    protected static ?string $modelLabel = 'orden de venta';

    protected static ?string $pluralModelLabel = 'órdenes de venta';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('sales.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('sales.create') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('sales.update') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('sales.delete') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('customer_id')
                ->label('Cliente')
                ->options(fn () => Customer::query()->where('is_active', true)->pluck('name', 'id'))
                ->searchable(),
            Select::make('warehouse_id')
                ->label('Almacén origen')
                ->options(fn () => Warehouse::query()->where('is_active', true)->pluck('name', 'id'))
                ->required(),
            Select::make('status')
                ->label('Estado')
                ->options([
                    'draft' => 'Borrador',
                    'confirmed' => 'Confirmada',
                    'cancelled' => 'Cancelada',
                ])
                ->default('draft')
                ->disabled(fn (?SaleOrder $record) => $record?->status === 'confirmed')
                ->required(),
            DatePicker::make('order_date')
                ->label('Fecha')
                ->default(now())
                ->required(),
            Textarea::make('notes')
                ->label('Notas')
                ->rows(2)
                ->columnSpanFull(),
            Repeater::make('items')
                ->relationship()
                ->label('Productos')
                ->schema([
                    Select::make('product_id')
                        ->label('Producto')
                        ->options(fn () => Product::query()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    TextInput::make('quantity')
                        ->label('Cantidad')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    TextInput::make('unit_price')
                        ->label('Precio unitario')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                ])
                ->columns(3)
                ->required()
                ->minItems(1)
                ->columnSpanFull()
                ->disabled(fn (?SaleOrder $record) => $record?->status === 'confirmed'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->label('Número')->searchable()->sortable(),
                TextColumn::make('customer.name')->label('Cliente')->default('Mostrador'),
                TextColumn::make('warehouse.name')->label('Almacén'),
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'confirmed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => 'Borrador',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                        default => $state,
                    }),
                TextColumn::make('order_date')->label('Fecha')->date('d/m/Y')->sortable(),
            ])
            ->defaultSort('order_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSaleOrders::route('/'),
            'create' => CreateSaleOrder::route('/create'),
            'edit' => EditSaleOrder::route('/{record}/edit'),
        ];
    }
}
