<?php

namespace App\Repositories;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Carbon\Carbon;

class OrderRepository implements OrderRepositoryInterface
{
    /** @return EloquentCollection<int, Order> */
    public function index(): EloquentCollection
    {
        return Order::with('orderItems.product')->get();
    }

    public function show(string $id): Order
    {
        try {
            return Order::with('orderItems.product')->where('uuid', $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Order not found: ' . $e->getMessage());
        }
    }

    public function store(array $data): Order
    {
        try {
            return Order::create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create Order: ' . $e->getMessage());
        }
    }

    public function update(int $id, array $data): Order
    {
        try {
            $item = Order::findOrFail($id);
            $item->update($data);
            return $item;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Order not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to update order: ' . $e->getMessage());
        }
    }

    public function destroy(int $id): bool|null
    {
        try {
            $item = Order::findOrFail($id);
            return $item->delete();
        } catch (ModelNotFoundException $e) {
            throw new Exception('Order not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to delete order: ' . $e->getMessage());
        }
    }

    public function assignUserToOrdersByEmail(int $userId, string $email): int
    {
        try {
            return Order::where('email', $email)
                ->update(['user_id' => $userId]);
        } catch (\Exception $e) {
            throw new Exception('Failed to assign user to order: ' . $e->getMessage());
        }
    }
}
