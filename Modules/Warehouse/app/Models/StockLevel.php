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
     * en un almacén, creando la fila si todavía no existe. Registra el
     * movimiento en stock_movements para trazabilidad/auditoría.
     *
     * @param  string  $type  purchase|sale|transfer_in|transfer_out|adjustment
     * @param  \Illuminate\Database\Eloquent\Model|null  $reference  Modelo relacionado (orden de compra, venta, transferencia...)
     */
    public static function adjust(
        int|string $productId,
        int|string $warehouseId,
        int $delta,
        string $type = 'adjustment',
        ?Model $reference = null,
        ?string $note = null,
    ): self {
        $level = static::firstOrCreate(
            ['product_id' => (int) $productId, 'warehouse_id' => (int) $warehouseId],
            ['quantity' => 0],
        );

        $level->increment('quantity', $delta);
        $level = $level->fresh();

        StockMovement::create([
            'product_id' => (int) $productId,
            'warehouse_id' => (int) $warehouseId,
            'quantity_delta' => $delta,
            'quantity_after' => $level->quantity,
            'type' => $type,
            'reference_type' => $reference?->getMorphClass(),
            'reference_id' => $reference?->getKey(),
            'note' => $note,
            'user_id' => auth()->id(),
        ]);

        return $level;
    }
}
