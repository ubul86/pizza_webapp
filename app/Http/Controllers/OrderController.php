<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Traits\HandleJsonResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    use HandleJsonResponse;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): JsonResponse
    {
        $items = $this->orderService->index();
        return $this->successResponse(OrderResource::collection($items));
    }

    public function show(string $id): JsonResponse
    {
        try {
            $item = $this->orderService->show($id);
            return $this->successResponse(new OrderResource($item));
        } catch (Exception $e) {
            return $this->errorResponse($e, 404);
        }
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->orderService->store($validated);
            return $this->successResponse(new OrderResource($item), 201);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->orderService->update($validated, $id);
            return $this->successResponse(new OrderResource($item));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e, 404);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->orderService->destroy($id);
            return $this->successResponse(['message' => 'Order deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e, 404);
        }
    }
}
