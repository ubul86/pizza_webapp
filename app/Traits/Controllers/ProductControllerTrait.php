<?php

namespace App\Traits\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Traits\FormatsMeta;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use Exception;

trait ProductControllerTrait
{
    use HandleJsonResponse;
    /** @use FormatsMeta<Product> */
    use FormatsMeta;

    protected ProductService $productService;

    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->index($request->all());
        return $this->successResponse([
            'items' => ProductResource::collection($products->items()),
            'meta' => $this->formatMeta($products)
        ]);
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
