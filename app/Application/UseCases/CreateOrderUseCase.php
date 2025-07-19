<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Order;
use App\Domain\Entities\OrderItem;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Repositories\InventoryRepositoryInterface;
use App\Domain\Services\CartService;
use App\Domain\Services\EmailServiceInterface;
use App\Application\DTOs\CreateOrderDTO;
use App\Application\Services\OrderNumberGeneratorService;
use Illuminate\Support\Str;

class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private InventoryRepositoryInterface $inventoryRepository,
        private CartService $cartService,
        private EmailServiceInterface $emailService,
        private OrderNumberGeneratorService $orderNumberGenerator
    ) {}

    public function execute(CreateOrderDTO $orderData): Order
    {
        $cart = $this->cartService->getCart();
        
        if (empty($cart)) {
            throw new \InvalidArgumentException('Carrinho vazio');
        }

        $order = $this->createOrder($orderData);
        $this->createOrderItems($order, $cart);
        $this->updateInventory($cart);
        $this->updateCouponUsage();
        $this->sendOrderConfirmationEmail($order);
        $this->cartService->clearCart();

        return $order;
    }

    private function createOrder(CreateOrderDTO $orderData): Order
    {
        $orderDataArray = $orderData->toArray();
        
        return $this->orderRepository->create([
            'order_number' => $this->orderNumberGenerator->generate(),
            'status' => 'pending',
            'subtotal' => $this->cartService->getSubtotal(),
            'shipping_cost' => $this->cartService->getShippingCost(),
            'discount' => $this->cartService->getDiscount(),
            'total' => $this->cartService->getTotal(),
            'coupon_id' => $this->cartService->getAppliedCoupon()?->id,
            'customer_name' => $orderDataArray['customer_name'],
            'customer_email' => $orderDataArray['customer_email'],
            'customer_phone' => $orderDataArray['customer_phone'],
            'shipping_address' => $orderDataArray['address'] . ', ' . $orderDataArray['number'] . ($orderDataArray['complement'] ? ' - ' . $orderDataArray['complement'] : ''),
            'shipping_city' => $orderDataArray['city'],
            'shipping_state' => $orderDataArray['state'],
            'shipping_zipcode' => $orderDataArray['cep'],
            'shipping_country' => 'Brasil'
        ]);
    }

    private function createOrderItems(Order $order, array $cart): void
    {
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variation' => $item['variation'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['price'] * $item['quantity']
            ]);
        }
    }

    private function updateInventory(array $cart): void
    {
        foreach ($cart as $item) {
            $this->inventoryRepository->decreaseStock(
                $item['product_id'],
                $item['variation'],
                $item['quantity']
            );
        }
    }

    private function updateCouponUsage(): void
    {
        $coupon = $this->cartService->getAppliedCoupon();
        if ($coupon) {
            $coupon->incrementUsage();
        }
    }

    private function sendOrderConfirmationEmail(Order $order): void
    {
        $this->emailService->sendOrderConfirmation($order);
    }
} 