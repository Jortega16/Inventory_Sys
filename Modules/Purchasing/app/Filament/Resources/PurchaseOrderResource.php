<?php

declare(strict_types=1);

namespace Modules\Purchasing\Filament\Resources;

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
use Modules\Purchasing\Filament\Resources\PurchaseOrderResource\Pages\CreatePurchaseOrder;
use Modules\Purchasing\Filament\Resources\PurchaseOrderResource\Pages\EditPurchaseOrder;
use Modules\Purchasing\Filament\Resources\PurchaseOrderResource\Pages\ListPurchaseOrders;
use Modules\Purchasing\Models\PurchaseOrder;
use Modules\Purchasing\Models\Supplier;
use Modules\Warehouse\Models\Warehouse;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Compras';

    protected static ?string $navigationLabel = 'Órdenes de compra';

    protected static ?string $modelLabel = 'orden de compra';

    protected static ?string $pluralModelLabel = 'órdenes de compra';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('purchasing.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('purchasing.create') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('purchasing.update') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('purchasing.delete') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('supplier_id')
                ->label('Proveedor')
                ->options(fn () => Supplier::query()->where('is_active', true)->pluck('name', 'id'))
                ->searchable()
                ->required(),
            Select::make('warehouse_id')
                ->label('Almacén destino')
                ->options(fn () => Warehouse::query()->where('is_active', true)->pluck('name', 'id'))
                ->required(),
            Select::make('status')
                ->label('Estado')
                ->options([
                    'draft' => 'Borrador',
                    'sent' => 'Enviada',
                    'received' => 'Recibida',
                    'cancelled' => 'Cancelada',
                ])
                ->default('draft')
                ->disabled(fn (?PurchaseOrder $record) => $record?->status === 'received')
                ->required(),
            DatePicker::make('order_date')
                ->label('Fecha de orden')
                ->default(now())
                ->required(),
            DatePicker::make('expected_date')
                ->label('Fecha esperada'),
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
                    TextInput::make('unit_cost')
                        ->label('Costo unitario')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                ])
                ->columns(3)
                ->required()
                ->minItems(1)
                ->columnSpanFull()
                ->disabled(fn (?PurchaseOrder $record) => $record?->status === 'received'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->label('Número')->searchable()->sortable(),
                TextColumn::make('supplier.name')->label('Proveedor')->searchable(),
                TextColumn::make('warehouse.name')->label('Almacén'),
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'sent',
                        'success' => 'received',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => 'Borrador',
                        'sent' => 'Enviada',
                        'received' => 'Recibida',
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
            'index' => ListPurchaseOrders::route('/'),
            'create' => CreatePurchaseOrder::route('/create'),
            'edit' => EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
