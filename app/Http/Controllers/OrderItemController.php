<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderItemResource;
use App\Services\OrderItemService;
use App\Traits\HandleJsonResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreOrderItemRequest;
use App\Http\Requests\UpdateOrderItemRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    use HandleJsonResponse;

    protected OrderItemService $orderItemService;

    public function __construct(OrderItemService $orderItemService)
    {
        $this->orderItemService = $orderItemService;
    }

    public function index(Request $request, Order $order): JsonResponse
    {
        $items = $this->orderItemService->index($order->id, $request->all());
        return $this->successResponse(OrderItemResource::collection($items));
    }

    public function show(Order $order, int $id): JsonResponse
    {
        try {
            $item = $this->orderItemService->show($order->id, $id);
            return $this->successResponse(new OrderItemResource($item));
        } catch (Exception $e) {
            return $this->errorResponse($e, 404);
        }
    }

    public function store(Order $order, StoreOrderItemRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->orderItemService->store($order->id, $validated);
            return $this->successResponse(new OrderItemResource($item), 201);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function update(Order $order, UpdateOrderItemRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->orderItemService->update($order->id, $validated, $id);
            return $this->successResponse(new OrderItemResource($item));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e, 404);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function destroy(Order $order, int $id): JsonResponse
    {
        try {
            $this->orderItemService->destroy($order->id, $id);
            return $this->successResponse(['message' => 'Order Item deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e, 404);
        }
    }
}
