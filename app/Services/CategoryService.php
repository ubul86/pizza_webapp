<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryService
{
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /** @return EloquentCollection<int, Category> */
    public function index(): EloquentCollection
    {
        return $this->categoryRepository->index();
    }

    public function show(int $id): Category
    {
        try {
            return $this->categoryRepository->show($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(array $data): Category
    {
        try {
            return $this->categoryRepository->store($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(array $data, int $id): Category
    {
        try {
            return $this->categoryRepository->update($id, $data);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function destroy(int $id): bool|null
    {
        try {
            return $this->categoryRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
