<?php

namespace App\Services;

use App\Mail\OrderSuccessMail;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\OrderItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /** @return EloquentCollection<int, Order> */
    public function index(): EloquentCollection
    {
        return $this->orderRepository->index();
    }

    public function show(string $id): Order
    {
        try {
            return $this->orderRepository->show($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(array $data): Order
    {
        DB::beginTransaction();
        try {
            $order = $this->orderRepository->store($data);

            $collectedData = collect($data);

            (new Collection($collectedData->get('items', [])))->whenNotEmpty(function ($items) use ($order) {
                $order->orderItems()->createMany($items->toArray());
            });

            if ($collectedData->has('email_address')) {
                Mail::to($collectedData->get('email_address'))->send(new OrderSuccessMail($order));
            }

            DB::commit();

            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(array $data, int $id): Order
    {
        try {
            return $this->orderRepository->update($id, $data);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function destroy(int $id): bool|null
    {
        try {
            return $this->orderRepository->destroy($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
