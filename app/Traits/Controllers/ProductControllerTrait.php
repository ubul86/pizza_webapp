<?php

namespace App\Traits\Controllers;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use Exception;

trait ProductControllerTrait
{
    protected ProductService $productService;

    public function index(): JsonResponse
    {
        $products = $this->productService->index();
        return response()->json(ProductResource::collection($products));
    }

    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->show($id);
            return response()->json(new ProductResource($product));
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }
}
