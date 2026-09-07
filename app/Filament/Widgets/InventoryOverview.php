<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Catalog\Models\Product;
use Modules\Sales\Models\SaleOrder;
use Modules\Warehouse\Models\StockLevel;

class InventoryOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Valorización del inventario = suma(cantidad * costo promedio) de cada línea de stock.
        $valuation = StockLevel::query()
            ->join('products', 'products.id', '=', 'stock_levels.product_id')
            ->selectRaw('COALESCE(SUM(stock_levels.quantity * products.average_cost), 0) as total')
            ->value('total');

        $lowStockCount = Product::query()
            ->where('is_active', true)
            ->where('reorder_point', '>', 0)
            ->get()
            ->filter(fn (Product $product) => $product->totalStock() <= $product->reorder_point)
            ->count();

        $salesThisMonth = SaleOrder::query()
            ->where('status', 'confirmed')
            ->whereBetween('confirmed_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->with('items')
            ->get()
            ->sum(fn (SaleOrder $order) => $order->items->sum(fn ($item) => $item->quantity * $item->unit_price));

        return [
            Stat::make('Valorización de inventario', '$' . number_format((float) $valuation, 2))
                ->description('Costo promedio × existencias')
                ->icon('heroicon-o-banknotes')
                ->color('success'),
            Stat::make('Productos con stock bajo', (string) $lowStockCount)
                ->description('En o por debajo del punto de reorden')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),
            Stat::make('Ventas del mes', '$' . number_format((float) $salesThisMonth, 2))
                ->description(now()->translatedFormat('F Y'))
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),
        ];
    }
}
