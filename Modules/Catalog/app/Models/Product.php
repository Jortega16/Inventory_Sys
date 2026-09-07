<?php

declare(strict_types=1);

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Warehouse\Models\StockLevel;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'description',
        'unit',
        'cost_price',
        'average_cost',
        'sale_price',
        'reorder_point',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'average_cost' => 'decimal:4',
        'sale_price' => 'decimal:2',
        'reorder_point' => 'integer',
        'is_active' => 'boolean',
    ];

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    /**
     * Suma de existencias en todos los almacenes.
     */
    public function totalStock(): int
    {
        return (int) $this->stockLevels()->sum('quantity');
    }

    /**
     * Recalcula el costo promedio ponderado tras recibir una compra.
     * Debe llamarse ANTES de sumar la cantidad al stock (usa el stock previo).
     */
    public function applyPurchaseCost(int $quantityReceived, float $unitCost, ?int $priorTotalStock = null): void
    {
        $priorTotalStock ??= $this->totalStock();

        $newTotal = $priorTotalStock + $quantityReceived;

        $newAverage = $newTotal > 0
            ? (((float) $this->average_cost * $priorTotalStock) + ($unitCost * $quantityReceived)) / $newTotal
            : $unitCost;

        $this->update(['average_cost' => $newAverage]);
    }
}
