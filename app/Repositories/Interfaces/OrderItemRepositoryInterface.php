<?php

namespace App\Repositories\Interfaces;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface OrderItemRepositoryInterface
{
    /** @return EloquentCollection<int, OrderItem> */
    public function index(int $id, array $data): EloquentCollection;
    public function show(int $orderId, int $id): OrderItem;
    public function store(int $orderId, array $data): OrderItem;
    public function update(int $orderId, int $id, array $data): OrderItem;
    public function destroy(int $orderId, int $id): bool|null;
}
