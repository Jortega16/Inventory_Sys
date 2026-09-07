<?php

declare(strict_types=1);

namespace Modules\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Sales\Models\SaleOrder;
use Modules\Warehouse\Models\Warehouse;

class SaleOrderFactory extends Factory
{
    protected $model = SaleOrder::class;

    public function definition(): array
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'status' => 'draft',
            'order_date' => now(),
        ];
    }
}
