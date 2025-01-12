<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface CategoryRepositoryInterface
{
    /** @return EloquentCollection<int, Category> */
    public function index(): EloquentCollection;
    public function show(int $id): Category;
    public function store(array $data): Category;
    public function update(int $id, array $data): Category;
    public function destroy(int $id): bool|null;
}
