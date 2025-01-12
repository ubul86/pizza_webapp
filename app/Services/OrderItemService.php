<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderItemService
{
    protected OrderItemRepositoryInterface $orderItemRepository;

    public function __construct(OrderItemRepositoryInterface $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    /** @return EloquentCollection<int, OrderItem> */
    public function index(int $id, array $data): EloquentCollection
    {
        return $this->orderItemRepository->index($id, $data);
    }

    public function show(int $orderId, int $id): OrderItem
    {
        try {
            return $this->orderItemRepository->show($orderId, $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(int $orderId, array $data): OrderItem
    {
        try {
            return $this->orderItemRepository->store($orderId, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(int $orderId, array $data, int $id): OrderItem
    {
        try {
            return $this->orderItemRepository->update($orderId, $id, $data);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function destroy(int $orderId, int $id): bool|null
    {
        try {
            return $this->orderItemRepository->destroy($orderId, $id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
