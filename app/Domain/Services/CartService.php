<?php

namespace App\Domain\Services;

use App\Domain\Entities\Product;
use App\Domain\Entities\Coupon;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const CART_KEY = 'cart';
    private const COUPON_KEY = 'applied_coupon';

    public function addItem(Product $product, int $quantity, ?string $variation = null): void
    {
        $cart = $this->getCart();
        $itemKey = $this->generateItemKey($product->id, $variation);

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += $quantity;
        } else {
            $cart[$itemKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'variation' => $variation,
                'quantity' => $quantity
            ];
        }

        $this->saveCart($cart);
    }

    public function removeItem(int $productId, ?string $variation = null): void
    {
        $cart = $this->getCart();
        $itemKey = $this->generateItemKey($productId, $variation);
        unset($cart[$itemKey]);
        $this->saveCart($cart);
    }

    public function updateQuantity(int $productId, int $quantity, ?string $variation = null): void
    {
        $cart = $this->getCart();
        $itemKey = $this->generateItemKey($productId, $variation);

        if (isset($cart[$itemKey])) {
            if ($quantity <= 0) {
                unset($cart[$itemKey]);
            } else {
                $cart[$itemKey]['quantity'] = $quantity;
            }
        }

        $this->saveCart($cart);
    }

    public function getCart(): array
    {
        return Session::get(self::CART_KEY, []);
    }

    public function clearCart(): void
    {
        Session::forget(self::CART_KEY);
        Session::forget(self::COUPON_KEY);
    }

    public function getSubtotal(): float
    {
        $cart = $this->getCart();
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return $subtotal;
    }

    public function getShippingCost(): float
    {
        $subtotal = $this->getSubtotal();

        if ($subtotal >= 200.00) {
            return 0.00;
        }

        if ($subtotal >= 52.00 && $subtotal <= 166.59) {
            return 15.00;
        }

        return 20.00;
    }

    public function getDiscount(): float
    {
        $coupon = $this->getAppliedCoupon();
        if (!$coupon) {
            return 0.00;
        }

        $subtotal = $this->getSubtotal();
        return $coupon->calculateDiscount($subtotal);
    }

    public function getTotal(): float
    {
        return $this->getSubtotal() + $this->getShippingCost() - $this->getDiscount();
    }

    public function applyCoupon(Coupon $coupon): bool
    {
        $subtotal = $this->getSubtotal();
        
        if (!$coupon->canBeAppliedTo($subtotal)) {
            return false;
        }

        Session::put(self::COUPON_KEY, $coupon->id);
        return true;
    }

    public function removeCoupon(): void
    {
        Session::forget(self::COUPON_KEY);
    }

    public function getAppliedCoupon(): ?Coupon
    {
        $couponId = Session::get(self::COUPON_KEY);
        return $couponId ? Coupon::find($couponId) : null;
    }

    private function saveCart(array $cart): void
    {
        Session::put(self::CART_KEY, $cart);
    }

    private function generateItemKey(int $productId, ?string $variation): string
    {
        return $productId . '_' . ($variation ?? 'default');
    }
} 