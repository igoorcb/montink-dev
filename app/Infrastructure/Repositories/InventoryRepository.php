<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Inventory;
use App\Domain\Repositories\InventoryRepositoryInterface;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function findById(int $id): ?Inventory
    {
        return Inventory::find($id);
    }

    public function findByProductAndVariation(int $productId, ?string $variation): ?Inventory
    {
        return Inventory::where('product_id', $productId)
            ->where('variation', $variation)
            ->first();
    }

    public function findAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Inventory::all();
    }

    public function create(array $data): Inventory
    {
        return Inventory::create($data);
    }

    public function update(Inventory $inventory, array $data): Inventory
    {
        $inventory->update($data);
        return $inventory;
    }

    public function delete(Inventory $inventory): bool
    {
        return $inventory->delete();
    }

    public function decreaseStock(int $productId, ?string $variation, int $quantity): bool
    {
        $inventory = $this->findByProductAndVariation($productId, $variation);
        
        if (!$inventory || $inventory->quantity < $quantity) {
            return false;
        }

        $inventory->decreaseStock($quantity);
        return true;
    }

    public function increaseStock(int $productId, ?string $variation, int $quantity): bool
    {
        $inventory = $this->findByProductAndVariation($productId, $variation);
        
        if (!$inventory) {
            return false;
        }

        $inventory->increaseStock($quantity);
        return true;
    }
} 