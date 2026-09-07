<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Warehouse\Models\StockLevel;

class StockController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()->can('warehouse.view'), 403);

        $levels = StockLevel::query()
            ->with(['product:id,sku,name', 'warehouse:id,name'])
            ->when($request->filled('warehouse_id'), fn ($q) => $q->where('warehouse_id', $request->integer('warehouse_id')))
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->integer('product_id')))
            ->paginate($request->integer('per_page', 50));

        return response()->json($levels->through(fn (StockLevel $level) => [
            'product' => ['id' => $level->product->id, 'sku' => $level->product->sku, 'name' => $level->product->name],
            'warehouse' => ['id' => $level->warehouse->id, 'name' => $level->warehouse->name],
            'quantity' => $level->quantity,
        ]));
    }
}
