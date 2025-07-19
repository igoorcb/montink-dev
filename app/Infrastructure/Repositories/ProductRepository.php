<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Product;
use App\Domain\Repositories\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        return Product::with('inventory')->find($id);
    }

    public function findAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('inventory')->where('active', true)->get();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function findActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('inventory')->where('active', true)->get();
    }
} 