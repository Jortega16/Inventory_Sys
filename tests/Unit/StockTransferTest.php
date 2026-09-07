<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Validation\ValidationException;
use Modules\Catalog\Models\Product;
use Modules\Warehouse\Models\StockLevel;
use Modules\Warehouse\Models\StockTransfer;
use Modules\Warehouse\Models\Warehouse;
use Tests\TenantTestCase;

class StockTransferTest extends TenantTestCase
{
    public function test_transfer_moves_quantity_between_warehouses(): void
    {
        $origin = Warehouse::factory()->create();
        $destination = Warehouse::factory()->create();
        $product = Product::factory()->create();
        StockLevel::adjust($product->id, $origin->id, 30);

        StockTransfer::create([
            'product_id' => $product->id,
            'from_warehouse_id' => $origin->id,
            'to_warehouse_id' => $destination->id,
            'quantity' => 12,
        ]);

        $this->assertSame(18, StockLevel::where('warehouse_id', $origin->id)->value('quantity'));
        $this->assertSame(12, StockLevel::where('warehouse_id', $destination->id)->value('quantity'));
    }

    public function test_transfer_is_rejected_when_origin_lacks_stock(): void
    {
        $origin = Warehouse::factory()->create();
        $destination = Warehouse::factory()->create();
        $product = Product::factory()->create();
        StockLevel::adjust($product->id, $origin->id, 5);

        $this->expectException(ValidationException::class);

        StockTransfer::create([
            'product_id' => $product->id,
            'from_warehouse_id' => $origin->id,
            'to_warehouse_id' => $destination->id,
            'quantity' => 6,
        ]);

        $this->assertDatabaseCount('stock_transfers', 0);
    }
}
