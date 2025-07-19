<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Entities\Product;
use App\Domain\Entities\Inventory;
use App\Domain\Entities\Coupon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Smartphone Galaxy S23',
                'price' => 2999.99,
                'description' => 'Smartphone Samsung Galaxy S23 com 128GB',
                'variations' => ['Preto', 'Branco', 'Verde'],
                'stock' => [
                    ['variation' => 'Preto', 'quantity' => 50, 'min_quantity' => 5],
                    ['variation' => 'Branco', 'quantity' => 30, 'min_quantity' => 5],
                    ['variation' => 'Verde', 'quantity' => 25, 'min_quantity' => 5]
                ]
            ],
            [
                'name' => 'Notebook Dell Inspiron',
                'price' => 4599.99,
                'description' => 'Notebook Dell Inspiron 15" Intel i5 8GB 256GB SSD',
                'variations' => ['Intel i5', 'Intel i7'],
                'stock' => [
                    ['variation' => 'Intel i5', 'quantity' => 20, 'min_quantity' => 3],
                    ['variation' => 'Intel i7', 'quantity' => 15, 'min_quantity' => 3]
                ]
            ],
            [
                'name' => 'Fone de Ouvido Bluetooth',
                'price' => 299.99,
                'description' => 'Fone de ouvido sem fio com cancelamento de ruído',
                'variations' => null,
                'stock' => [
                    ['variation' => null, 'quantity' => 100, 'min_quantity' => 10]
                ]
            ],
            [
                'name' => 'Smart TV 55" 4K',
                'price' => 2899.99,
                'description' => 'Smart TV Samsung 55" 4K Ultra HD',
                'variations' => null,
                'stock' => [
                    ['variation' => null, 'quantity' => 25, 'min_quantity' => 3]
                ]
            ],
            [
                'name' => 'Mouse Gamer RGB',
                'price' => 199.99,
                'description' => 'Mouse gamer com RGB e 6 botões programáveis',
                'variations' => ['Preto', 'Branco'],
                'stock' => [
                    ['variation' => 'Preto', 'quantity' => 80, 'min_quantity' => 10],
                    ['variation' => 'Branco', 'quantity' => 60, 'min_quantity' => 10]
                ]
            ]
        ];

        foreach ($products as $productData) {
            $stock = $productData['stock'];
            unset($productData['stock']);
            
            $product = Product::create($productData);
            
            foreach ($stock as $stockData) {
                Inventory::create([
                    'product_id' => $product->id,
                    'variation' => $stockData['variation'],
                    'quantity' => $stockData['quantity'],
                    'min_quantity' => $stockData['min_quantity']
                ]);
            }
        }

        $coupons = [
            [
                'code' => 'DESCONTO10',
                'description' => 'Desconto de 10% em compras acima de R$ 100',
                'type' => 'percentage',
                'value' => 10.00,
                'min_amount' => 100.00,
                'max_uses' => 100,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3)
            ],
            [
                'code' => 'FRETE0',
                'description' => 'Frete grátis em compras acima de R$ 150',
                'type' => 'fixed',
                'value' => 20.00,
                'min_amount' => 150.00,
                'max_uses' => 50,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(2)
            ],
            [
                'code' => 'MEGA50',
                'description' => 'Desconto de R$ 50 em compras acima de R$ 500',
                'type' => 'fixed',
                'value' => 50.00,
                'min_amount' => 500.00,
                'max_uses' => 25,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(1)
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}
