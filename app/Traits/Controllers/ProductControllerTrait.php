<?php

namespace App\Traits\Controllers;

use App\Services\ProductService;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use Exception;

trait ProductControllerTrait
{
    use HandleJsonResponse;

    protected ProductService $productService;

    public function index(): JsonResponse
    {
        $products = $this->productService->index();
        return $this->successResponse(ProductResource::collection($products));
    }

    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->show($id);
            return $this->successResponse(new ProductResource($product));
        } catch (Exception $e) {
            return $this->errorResponse($e, 404);
        }
    }
}
