<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use App\Models\Product;

interface ProductRepositoryInterface
{
    /** @return EloquentCollection<int, Product> */
    public function index(): EloquentCollection;
    public function show(int $id): Product;
    public function store(array $data): Product;
    public function update(int $id, array $data): Product;
    public function destroy(int $id): bool|null;
    public function bulkDestroy(array $ids): bool;
}
