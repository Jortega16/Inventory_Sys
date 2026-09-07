<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Sales\Models\SaleOrder;

class SaleOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()->can('sales.view'), 403);

        $orders = SaleOrder::query()
            ->with(['customer:id,name', 'warehouse:id,name'])
            ->latest('order_date')
            ->paginate($request->integer('per_page', 25));

        return response()->json($orders->through(fn (SaleOrder $order) => $this->present($order)));
    }

    public function show(Request $request, SaleOrder $saleOrder): JsonResponse
    {
        abort_unless($request->user()->can('sales.view'), 403);

        return response()->json($this->present($saleOrder->load('items.product:id,sku,name')));
    }

    /**
     * Crea una orden de venta en borrador (no toca stock todavía).
     * Ejemplo de payload:
     * {
     *   "warehouse_id": 1,
     *   "customer_id": null,
     *   "items": [{"product_id": 1, "quantity": 2, "unit_price": 9.99}]
     * }
     */
    public function store(Request $request): JsonResponse
    {
        abort_unless($request->user()->can('sales.create'), 403);

        $data = $request->validate([
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'order_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $order = DB::transaction(function () use ($data) {
            $order = SaleOrder::create([
                'warehouse_id' => $data['warehouse_id'],
                'customer_id' => $data['customer_id'] ?? null,
                'order_date' => $data['order_date'] ?? now(),
                'notes' => $data['notes'] ?? null,
                'status' => 'draft',
            ]);

            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        return response()->json($this->present($order->load('items.product:id,sku,name')), 201);
    }

    /**
     * Confirma la venta: valida y descuenta stock. Devuelve 422 si no
     * hay suficiente stock, con el detalle del producto/cantidad faltante.
     */
    public function confirm(Request $request, SaleOrder $saleOrder): JsonResponse
    {
        abort_unless($request->user()->can('sales.create'), 403);

        try {
            $saleOrder->confirmSale();
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'No se pudo confirmar la venta.',
                'errors' => $exception->errors(),
            ], 422);
        }

        return response()->json($this->present($saleOrder->fresh(['items.product:id,sku,name'])));
    }

    private function present(SaleOrder $order): array
    {
        return [
            'id' => $order->id,
            'number' => $order->number,
            'status' => $order->status,
            'customer' => $order->customer?->name,
            'warehouse' => $order->warehouse?->name,
            'order_date' => $order->order_date?->toDateString(),
            'confirmed_at' => $order->confirmed_at?->toIso8601String(),
            'items' => $order->relationLoaded('items')
                ? $order->items->map(fn ($item) => [
                    'product' => $item->product?->name,
                    'quantity' => $item->quantity,
                    'unit_price' => (float) $item->unit_price,
                ])
                : null,
        ];
    }
}
