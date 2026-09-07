<?php

declare(strict_types=1);

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Warehouse\Models\StockLevel;
use Modules\Warehouse\Models\Warehouse;

class SaleOrder extends Model
{
    protected $fillable = [
        'number',
        'customer_id',
        'warehouse_id',
        'status',
        'order_date',
        'notes',
        'confirmed_at',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'warehouse_id' => 'integer',
        'order_date' => 'date',
        'confirmed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (SaleOrder $order) {
            $order->number ??= 'SO-'.str_pad((string) ((static::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT);
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleOrderItem::class);
    }

    /**
     * Confirma la venta: valida que haya stock suficiente de cada línea en
     * el almacén de origen y lo descuenta. Idempotente si ya está confirmada.
     */
    public function confirmSale(): void
    {
        if ($this->status === 'confirmed') {
            return;
        }

        DB::transaction(function () {
            foreach ($this->items as $item) {
                $available = StockLevel::firstOrCreate(
                    ['product_id' => $item->product_id, 'warehouse_id' => $this->warehouse_id],
                    ['quantity' => 0],
                )->quantity;

                if ($available < $item->quantity) {
                    throw ValidationException::withMessages([
                        'status' => "Stock insuficiente para \"{$item->product?->name}\" (disponible: {$available}, se necesitan {$item->quantity}).",
                    ]);
                }
            }

            foreach ($this->items as $item) {
                StockLevel::adjust($item->product_id, $this->warehouse_id, -$item->quantity, 'sale', $this);
            }

            $this->update(['status' => 'confirmed', 'confirmed_at' => now()]);
        });
    }
}
