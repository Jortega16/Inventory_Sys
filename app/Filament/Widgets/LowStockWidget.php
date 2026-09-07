<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Modules\Catalog\Models\Product;

class LowStockWidget extends BaseWidget
{
    protected static ?string $heading = 'Productos con stock bajo';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('is_active', true)
                    ->where('reorder_point', '>', 0)
                    ->whereRaw('reorder_point >= (select coalesce(sum(quantity), 0) from stock_levels where stock_levels.product_id = products.id)')
            )
            ->columns([
                Tables\Columns\TextColumn::make('sku')->label('SKU'),
                Tables\Columns\TextColumn::make('name')->label('Producto'),
                Tables\Columns\TextColumn::make('reorder_point')->label('Punto de reorden'),
                Tables\Columns\TextColumn::make('stock_actual')
                    ->label('Stock actual')
                    ->state(fn (Product $record) => $record->totalStock())
                    ->badge()
                    ->color(fn (Product $record) => $record->totalStock() <= $record->reorder_point ? 'danger' : 'success'),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }

    public static function canView(): bool
    {
        return auth()->user()?->can('catalog.view') ?? false;
    }
}
