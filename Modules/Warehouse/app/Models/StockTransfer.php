<?php

declare(strict_types=1);

namespace Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Catalog\Models\Product;

class StockTransfer extends Model
{
    protected $fillable = [
        'product_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'quantity',
        'note',
        'transferred_at',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'from_warehouse_id' => 'integer',
        'to_warehouse_id' => 'integer',
        'quantity' => 'integer',
        'transferred_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Mover el stock real ocurre atómicamente al crear la transferencia:
        // se resta del almacén origen y se suma al destino en una sola transacción.
        static::creating(function (StockTransfer $transfer) {
            $transfer->transferred_at ??= now();

            $available = StockLevel::firstOrCreate(
                ['product_id' => $transfer->product_id, 'warehouse_id' => $transfer->from_warehouse_id],
                ['quantity' => 0],
            )->quantity;

            if ($available < $transfer->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "No hay suficiente stock en el almacén de origen (disponible: {$available}).",
                ]);
            }
        });

        static::created(function (StockTransfer $transfer) {
            DB::transaction(function () use ($transfer) {
                StockLevel::adjust($transfer->product_id, $transfer->from_warehouse_id, -$transfer->quantity, 'transfer_out', $transfer);
                StockLevel::adjust($transfer->product_id, $transfer->to_warehouse_id, $transfer->quantity, 'transfer_in', $transfer);
            });
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }
}
