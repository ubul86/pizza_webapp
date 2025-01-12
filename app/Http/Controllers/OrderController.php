<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): JsonResponse
    {
        $items = $this->orderService->index();
        return response()->json(OrderResource::collection($items));
    }

    public function show(string $id): JsonResponse
    {
        try {
            $item = $this->orderService->show($id);
            return response()->json(new OrderResource($item));
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->orderService->store($validated);
            return response()->json(new OrderResource($item), 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->orderService->update($validated, $id);
            return response()->json(new OrderResource($item));
        } catch (ModelNotFoundException $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->orderService->destroy($id);
            return response()->json(['message' => 'Order deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }
}
