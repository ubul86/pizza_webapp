<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CategoryRepository implements CategoryRepositoryInterface
{
    /** @return EloquentCollection<int, Category> */
    public function index(): EloquentCollection
    {
        return Category::get();
    }

    public function show(int $id): Category
    {
        try {
            return Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Category not found: ' . $e->getMessage());
        }
    }

    public function store(array $data): Category
    {
        try {
            return Category::create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create Category: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data): Category
    {
        try {
            $item = Category::findOrFail($id);
            $item->update($data);
            return $item;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Category not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to update Category: ' . $e->getMessage());
        }
    }

    public function destroy(int $id): bool|null
    {
        try {
            $item = Category::findOrFail($id);
            return $item->delete();
        } catch (ModelNotFoundException $e) {
            throw new Exception('Category not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to delete Category: ' . $e->getMessage());
        }
    }
}
