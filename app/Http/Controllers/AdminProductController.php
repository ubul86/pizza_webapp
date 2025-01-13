<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadedImageRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

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

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $product = $this->productService->store($validated);
            return response()->json(new ProductResource($product), 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $product = $this->productService->update($validated, $id);
            return response()->json(new ProductResource($product));
        } catch (ModelNotFoundException $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->productService->destroy($id);
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }

    public function bulkDestroy(Request $request): JsonResponse
    {
        try {
            $ids = explode(',', $request->input('ids'));
            $this->productService->bulkDestroy($ids);
            return response()->json(['message' => 'Products deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }

    public function uploadImages(int $itemId, UploadedImageRequest $request): JsonResponse
    {
        try {
            /** @var array<int, \Illuminate\Http\UploadedFile>|null $files */
            $files = $request->file('images');

            $files = is_array($files) ? $files : [$files];
            $product = $this->productService->uploadImages($itemId, $files);
            return response()->json(new ProductResource($product));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to upload images'], 500);
        }
    }
}
