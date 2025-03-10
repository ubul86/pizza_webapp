<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /** @return CursorPaginator<Product>|LengthAwarePaginator<Product> */
    public function index(array $data, bool $isAdmin = false): CursorPaginator|LengthAwarePaginator;
    public function show(int $id): Product;
    public function store(array $data): Product;
    public function update(int $id, array $data): Product;
    public function destroy(int $id): bool|null;
    public function bulkDestroy(array $ids): bool;
}
