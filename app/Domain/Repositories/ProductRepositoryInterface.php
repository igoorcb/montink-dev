<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    public function findAll(): \Illuminate\Database\Eloquent\Collection;
    public function create(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): bool;
    public function findActive(): \Illuminate\Database\Eloquent\Collection;
} 