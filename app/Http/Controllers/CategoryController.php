<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use App\Traits\HandleJsonResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use HandleJsonResponse;

    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        $items = $this->categoryService->index();
        return $this->successResponse(CategoryResource::collection($items));
    }

    public function show(int $id): JsonResponse
    {
        try {
            $item = $this->categoryService->show($id);
            return $this->successResponse(new CategoryResource($item));
        } catch (Exception $e) {
            return $this->errorResponse($e, 404);
        }
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->categoryService->store($validated);
            return $this->successResponse(new CategoryResource($item), 201);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $item = $this->categoryService->update($validated, $id);
            return $this->successResponse(new CategoryResource($item));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e, 404);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->categoryService->destroy($id);
            return $this->successResponse(['message' => 'Category deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e, 404);
        }
    }
}
