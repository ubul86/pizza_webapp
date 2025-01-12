<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderItemResource;
use App\Services\OrderItemService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    protected OrderItemService $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }

    public function index(Request $request, Order $order): JsonResponse
    {
        $items = $this->orderItemService->index($order->id, $request->all());
        return response()->json(OrderItemResource::collection($items));
    }

    public function show(Order $order, int $id): JsonResponse
    {
        try {
            $item = $this->orderItemService->show($order->id, $id);
            return response()->json(new OrderItemResource($item));
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }

    public function store(Order $order, StoreOrderItemRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->orderItemService->store($order->id, $validated);
            return response()->json(new OrderItemResource($item), 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function update(Order $order, UpdateOrderItemRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->orderItemService->update($order->id, $validated, $id);
            return response()->json(new OrderItemResource($item));
        } catch (ModelNotFoundException $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function destroy(Order $order, int $id): JsonResponse
    {
        try {
            $this->orderItemService->destroy($order->id, $id);
            return response()->json(['message' => 'Order Item deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }
}
