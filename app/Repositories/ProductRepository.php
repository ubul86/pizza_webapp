<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Carbon\Carbon;

class ProductRepository implements ProductRepositoryInterface
{
    /** @return EloquentCollection<int, Product> */
    public function index(): EloquentCollection
    {
        return Product::with('images')->get();
    }

    public function show(int $id): Product
    {
        try {
            return Product::with('images')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Product not found: ' . $e->getMessage());
        }
    }

    public function store(array $data): Product
    {
        try {
            return Product::create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create product: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data): Product
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($data);
            return $product;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Product not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(int $id): bool|null
    {
        try {
            $product = Product::findOrFail($id);
            return $product->delete();
        } catch (ModelNotFoundException $e) {
            throw new Exception('Product not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to delete product: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(array $ids): bool
    {
        try {
            Product::whereIn('id', $ids)->delete();
            return true;
        } catch (Exception $e) {
            throw new Exception('Failed to delete product: ' . $e->getMessage());
        }
    }

    public function setBulkCompleted(array $ids): string
    {
        $now = Carbon::now();
        try {
            Product::whereIn('id', $ids)
                ->whereNull('completed_at')
                ->update(['completed_at' => $now]);
            return $now;
        } catch (Exception $e) {
            throw new Exception('Failed to set completed products: ' . $e->getMessage());
        }
    }
}
