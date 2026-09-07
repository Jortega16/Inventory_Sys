<?php

declare(strict_types=1);

namespace Tests\Unit;

use Modules\Catalog\Models\Product;
use Modules\Warehouse\Models\StockLevel;
use Modules\Warehouse\Models\StockMovement;
use Modules\Warehouse\Models\Warehouse;
use Tests\TenantTestCase;

class StockLevelTest extends TenantTestCase
{
    public function test_adjust_creates_the_row_when_it_does_not_exist(): void
    {
        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        $level = StockLevel::adjust($product->id, $warehouse->id, 10);

        $this->assertSame(10, $level->quantity);
        $this->assertDatabaseHas('stock_levels', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 10,
        ]);
    }

    public function test_adjust_accumulates_on_existing_row(): void
    {
        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        StockLevel::adjust($product->id, $warehouse->id, 10);
        $level = StockLevel::adjust($product->id, $warehouse->id, -4);

        $this->assertSame(6, $level->quantity);
    }

    public function test_adjust_accepts_string_ids_from_form_submissions(): void
    {
        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        // Livewire/Filament siempre manda los ids de un Select como string.
        $level = StockLevel::adjust((string) $product->id, (string) $warehouse->id, 5);

        $this->assertSame(5, $level->quantity);
    }

    public function test_adjust_logs_a_stock_movement(): void
    {
        $product = Product::factory()->create();
        $warehouse = Warehouse::factory()->create();

        StockLevel::adjust($product->id, $warehouse->id, 7, 'adjustment', null, 'conteo físico');

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity_delta' => 7,
            'quantity_after' => 7,
            'type' => 'adjustment',
            'note' => 'conteo físico',
        ]);
    }
}
