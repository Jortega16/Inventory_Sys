<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Catalog\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()->can('catalog.view'), 403);

        $products = Product::query()
            ->when($request->boolean('active_only'), fn ($q) => $q->where('is_active', true))
            ->when($request->string('search')->isNotEmpty(), fn ($q) => $q
                ->where('name', 'ilike', '%' . $request->string('search') . '%')
                ->orWhere('sku', 'ilike', '%' . $request->string('search') . '%'))
            ->paginate($request->integer('per_page', 25));

        return response()->json($products->through(fn (Product $product) => [
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'unit' => $product->unit,
            'sale_price' => (float) $product->sale_price,
            'average_cost' => (float) $product->average_cost,
            'stock_total' => $product->totalStock(),
            'reorder_point' => $product->reorder_point,
            'is_active' => $product->is_active,
        ]));
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        abort_unless($request->user()->can('catalog.view'), 403);

        return response()->json([
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'description' => $product->description,
            'unit' => $product->unit,
            'sale_price' => (float) $product->sale_price,
            'average_cost' => (float) $product->average_cost,
            'reorder_point' => $product->reorder_point,
            'is_active' => $product->is_active,
            'stock_by_warehouse' => $product->stockLevels()
                ->with('warehouse:id,name')
                ->get()
                ->map(fn ($level) => [
                    'warehouse' => $level->warehouse->name,
                    'quantity' => $level->quantity,
                ]),
        ]);
    }
}
