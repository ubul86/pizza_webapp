<?php

namespace App\Repositories;

use App\Repositories\Interfaces\OrderItemRepositoryInterface;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    /** @return EloquentCollection<int, OrderItem> */
    public function index(int $id, array $data): EloquentCollection
    {
        return OrderItem::get();
    }

    public function show(int $orderId, int $id): OrderItem
    {
        try {
            return OrderItem::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('OrderItem not found: ' . $e->getMessage());
        }
    }

    public function store(int $orderId, array $data): OrderItem
    {
        try {
            return OrderItem::create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create OrderItem: ' . $e->getMessage());
        }
    }

    public function update(int $orderId, int $id, array $data): OrderItem
    {
        try {
            $item = OrderItem::findOrFail($id);
            $item->update($data);
            return $item;
        } catch (ModelNotFoundException $e) {
            throw new Exception('OrderItem not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to update OrderItem: ' . $e->getMessage());
        }
    }

    public function destroy(int $orderId, int $id): bool|null
    {
        try {
            $item = OrderItem::findOrFail($id);
            return $item->delete();
        } catch (ModelNotFoundException $e) {
            throw new Exception('OrderItem not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to delete OrderItem: ' . $e->getMessage());
        }
    }
}
