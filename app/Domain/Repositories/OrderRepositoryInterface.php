<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Order;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;
    public function findAll(): \Illuminate\Database\Eloquent\Collection;
    public function create(array $data): Order;
    public function update(Order $order, array $data): Order;
    public function delete(Order $order): bool;
    public function findByStatus(string $status): \Illuminate\Database\Eloquent\Collection;
    public function findWithItems(int $id): ?Order;
} 