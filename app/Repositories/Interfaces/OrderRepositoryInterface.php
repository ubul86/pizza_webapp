<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface OrderRepositoryInterface
{
    /** @return EloquentCollection<int, Order> */
    public function index(): EloquentCollection;
    public function show(string $id): Order;
    public function store(array $data): Order;
    public function update(int $id, array $data): Order;
    public function destroy(int $id): bool|null;
}
