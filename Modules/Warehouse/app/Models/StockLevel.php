<?php

declare(strict_types=1);

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Catalog\Models\Product;

class StockLevel extends Model
{
    protected $fillable = ['product_id', 'warehouse_id', 'quantity'];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Suma (o resta, con $delta negativo) cantidad al stock de un producto
     * en un almacén, creando la fila si todavía no existe.
     */
    public static function adjust(int|string $productId, int|string $warehouseId, int $delta): self
    {
        $level = static::firstOrCreate(
            ['product_id' => (int) $productId, 'warehouse_id' => (int) $warehouseId],
            ['quantity' => 0],
        );

        $level->increment('quantity', $delta);

        return $level->fresh();
    }
}
