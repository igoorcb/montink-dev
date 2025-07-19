<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Inventory;

interface InventoryRepositoryInterface
{
    public function findById(int $id): ?Inventory;
    public function findByProductAndVariation(int $productId, ?string $variation): ?Inventory;
    public function findAll(): \Illuminate\Database\Eloquent\Collection;
    public function create(array $data): Inventory;
    public function update(Inventory $inventory, array $data): Inventory;
    public function delete(Inventory $inventory): bool;
    public function decreaseStock(int $productId, ?string $variation, int $quantity): bool;
    public function increaseStock(int $productId, ?string $variation, int $quantity): bool;
} 