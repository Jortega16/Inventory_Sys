<?php

declare(strict_types=1);

namespace Modules\Catalog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Catalog\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'unit' => 'unidad',
            'cost_price' => $this->faker->randomFloat(2, 1, 50),
            'average_cost' => 0,
            'sale_price' => $this->faker->randomFloat(2, 5, 100),
            'reorder_point' => 0,
            'is_active' => true,
        ];
    }
}
