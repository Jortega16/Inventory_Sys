<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Validation\ValidationException;
use Modules\Catalog\Models\Product;
use Modules\Sales\Models\SaleOrder;
use Modules\Warehouse\Models\StockLevel;
use Modules\Warehouse\Models\Warehouse;
use Tests\TenantTestCase;

class SaleOrderTest extends TenantTestCase
{
    public function test_confirming_decrements_stock(): void
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();
        StockLevel::adjust($product->id, $warehouse->id, 20);

        $order = SaleOrder::factory()->create(['warehouse_id' => $warehouse->id]);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 8, 'unit_price' => 5]);

        $order->confirmSale();

        $this->assertSame(12, StockLevel::where('product_id', $product->id)->value('quantity'));
        $this->assertSame('confirmed', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->confirmed_at);
    }

    public function test_confirming_throws_when_stock_is_insufficient(): void
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();
        StockLevel::adjust($product->id, $warehouse->id, 3);

        $order = SaleOrder::factory()->create(['warehouse_id' => $warehouse->id]);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 10, 'unit_price' => 5]);

        $this->expectException(ValidationException::class);

        $order->confirmSale();

        // El stock no debe haberse tocado.
        $this->assertSame(3, StockLevel::where('product_id', $product->id)->value('quantity'));
    }

    public function test_confirming_does_not_partially_apply_when_one_line_fails(): void
    {
        $warehouse = Warehouse::factory()->create();
        $plentiful = Product::factory()->create();
        $scarce = Product::factory()->create();
        StockLevel::adjust($plentiful->id, $warehouse->id, 100);
        StockLevel::adjust($scarce->id, $warehouse->id, 1);

        $order = SaleOrder::factory()->create(['warehouse_id' => $warehouse->id]);
        $order->items()->create(['product_id' => $plentiful->id, 'quantity' => 5, 'unit_price' => 1]);
        $order->items()->create(['product_id' => $scarce->id, 'quantity' => 50, 'unit_price' => 1]);

        try {
            $order->confirmSale();
        } catch (ValidationException) {
            // esperado
        }

        // Ninguna línea debe haberse descontado, ni siquiera la que sí alcanzaba.
        $this->assertSame(100, StockLevel::where('product_id', $plentiful->id)->value('quantity'));
        $this->assertSame('draft', $order->fresh()->status);
    }

    public function test_confirming_is_idempotent(): void
    {
        $warehouse = Warehouse::factory()->create();
        $product = Product::factory()->create();
        StockLevel::adjust($product->id, $warehouse->id, 20);

        $order = SaleOrder::factory()->create(['warehouse_id' => $warehouse->id]);
        $order->items()->create(['product_id' => $product->id, 'quantity' => 5, 'unit_price' => 1]);

        $order->confirmSale();
        $order->fresh()->confirmSale();

        $this->assertSame(15, StockLevel::where('product_id', $product->id)->value('quantity'));
    }
}
