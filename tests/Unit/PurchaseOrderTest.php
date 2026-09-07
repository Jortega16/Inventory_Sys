<?php

declare(strict_types=1);

namespace Tests\Unit;

use Modules\Catalog\Models\Product;
use Modules\Purchasing\Models\PurchaseOrder;
use Modules\Warehouse\Models\StockLevel;
use Modules\Warehouse\Models\Warehouse;
use Tests\TenantTestCase;

class PurchaseOrderTest extends TenantTestCase
{
    public function test_marking_as_received_increments_stock_for_each_line(): void
    {
        $warehouse = Warehouse::factory()->create();
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();

        $order = PurchaseOrder::factory()->create(['warehouse_id' => $warehouse->id]);
        $order->items()->create(['product_id' => $productA->id, 'quantity' => 10, 'unit_cost' => 2]);
        $order->items()->create(['product_id' => $productB->id, 'quantity' => 5, 'unit_cost' => 3]);

        $order->markAsReceived();

        $this->assertSame(10, StockLevel::where('product_id', $productA->id)->value('quantity'));
        $this->assertSame(5, StockLevel::where('product_id', $productB->id)->value('quantity'));
        $this->assertSame('received', $order->fresh()->status);
    }

    public function test_marking_as_received_updates_the_weighted_average_cost(): void
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create(['average_cost' => 0]);

        // Primera compra: 10 unidades a $2 -> promedio $2.
        $order1 = PurchaseOrder::factory()->create(['warehouse_id' => $warehouse->id]);
        $order1->items()->create(['product_id' => $product->id, 'quantity' => 10, 'unit_cost' => 2]);
        $order1->markAsReceived();

        $this->assertEquals(2.0, (float) $product->fresh()->average_cost);

        // Segunda compra: 10 unidades a $4 -> promedio ponderado (10*2 + 10*4) / 20 = $3.
        $order2 = PurchaseOrder::factory()->create(['warehouse_id' => $warehouse->id]);
        $order2->items()->create(['product_id' => $product->id, 'quantity' => 10, 'unit_cost' => 4]);
        $order2->markAsReceived();

        $this->assertEquals(3.0, (float) $product->fresh()->average_cost);
    }

    public function test_marking_as_received_is_idempotent(): void
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();

        $order = PurchaseOrder::factory()->create(['warehouse_id' => $warehouse->id]);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 10, 'unit_cost' => 2]);

        $order->markAsReceived();
        $order->fresh()->markAsReceived(); // no debe volver a sumar

        $this->assertSame(10, StockLevel::where('product_id', $product->id)->value('quantity'));
    }

    public function test_number_is_auto_generated_when_not_provided(): void
    {
        $order = PurchaseOrder::factory()->create();

        $this->assertMatchesRegularExpression('/^PO-\d{5}$/', $order->number);
    }
}
