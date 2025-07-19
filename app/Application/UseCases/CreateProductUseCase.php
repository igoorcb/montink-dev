<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Product;
use App\Domain\Entities\Inventory;
use App\Domain\Repositories\ProductRepositoryInterface;

class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(array $data): Product
    {
        $product = $this->productRepository->create([
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'variations' => $data['variations'] ?? null,
            'active' => true
        ]);

        if (isset($data['stock'])) {
            $this->createInventory($product, $data['stock']);
        }

        return $product;
    }

    private function createInventory(Product $product, array $stockData): void
    {
        if (isset($stockData['quantity'])) {
            Inventory::create([
                'product_id' => $product->id,
                'variation' => $stockData['variation'] ?? null,
                'quantity' => $stockData['quantity'],
                'min_quantity' => $stockData['min_quantity'] ?? 0
            ]);
        }
    }
} 