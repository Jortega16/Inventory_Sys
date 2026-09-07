<?php

declare(strict_types=1);

namespace Modules\Purchasing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Modules\Warehouse\Models\StockLevel;
use Modules\Warehouse\Models\Warehouse;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'number',
        'supplier_id',
        'warehouse_id',
        'status',
        'order_date',
        'expected_date',
        'notes',
        'received_at',
    ];

    protected $casts = [
        'supplier_id' => 'integer',
        'warehouse_id' => 'integer',
        'order_date' => 'date',
        'expected_date' => 'date',
        'received_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (PurchaseOrder $order) {
            $order->number ??= 'PO-'.str_pad((string) ((static::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT);
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Marca la orden como recibida y suma el stock de cada línea al
     * almacén de destino. Idempotente: no vuelve a sumar si ya estaba recibida.
     */
    public function markAsReceived(): void
    {
        if ($this->status === 'received') {
            return;
        }

        DB::transaction(function () {
            foreach ($this->items as $item) {
                StockLevel::adjust($item->product_id, $this->warehouse_id, $item->quantity);
            }

            $this->update(['status' => 'received', 'received_at' => now()]);
        });
    }
}
