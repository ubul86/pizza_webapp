<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadedImageRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductImageService;
use App\Services\ProductService;
use App\traits\controllers\ProductControllerTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    use ProductControllerTrait;

    protected ProductImageService $productImageService;

    public function __construct(ProductService $productService, ProductImageService $productImageService)
    {
        $this->productService = $productService;
        $this->productImageService = $productImageService;
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
            $product = $this->productImageService->uploadImages($itemId, $files);
            return response()->json(new ProductResource($product));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to upload images'], 500);
        }
    }

    public function setImageToFirst(Request $request): JsonResponse
    {
        try {
            $this->productImageService->setImageToFirst($request->get('productId'), $request->get('imageId'));
            return response()->json(['message' => 'Products deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }

    public function deleteImage(Request $request): JsonResponse
    {
        try {
            $this->productImageService->deleteImage($request->get('productId'), $request->get('imageId'));
            return response()->json(['message' => 'Products deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 404);
        }
    }
}
