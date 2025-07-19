<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Order;
use App\Domain\Repositories\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id): ?Order
    {
        return Order::with(['items.product', 'coupon'])->find($id);
    }

    public function findAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Order::with(['items.product', 'coupon'])->get();
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): Order
    {
        $order->update($data);
        return $order;
    }

    public function delete(Order $order): bool
    {
        return $order->delete();
    }

    public function findByStatus(string $status): \Illuminate\Database\Eloquent\Collection
    {
        return Order::with(['items.product', 'coupon'])
            ->where('status', $status)
            ->get();
    }

    public function findWithItems(int $id): ?Order
    {
        return Order::with(['items.product', 'coupon'])->find($id);
    }
} 