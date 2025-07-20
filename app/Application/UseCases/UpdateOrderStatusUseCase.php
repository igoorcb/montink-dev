<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Order;
use App\Domain\Repositories\OrderRepositoryInterface;
use App\Domain\Repositories\InventoryRepositoryInterface;
use App\Domain\Services\EmailServiceInterface;

class UpdateOrderStatusUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private InventoryRepositoryInterface $inventoryRepository,
        private EmailServiceInterface $emailService
    ) {}

    public function execute(int $orderId, string $newStatus): Order
    {
        $order = $this->orderRepository->findById($orderId);

        if (!$order) {
            throw new \InvalidArgumentException('Pedido não encontrado');
        }

        if ($newStatus === 'cancelled') {
            $this->restoreInventory($order);
            $this->emailService->sendOrderCancellation($order);
            $this->orderRepository->delete($order);
            return $order;
        }

        $order->updateStatus($newStatus);
        $this->emailService->sendOrderStatusUpdate($order, $newStatus);

        return $order;
    }

    private function restoreInventory(Order $order): void
    {
        foreach ($order->items as $item) {
            $this->inventoryRepository->increaseStock(
                $item->product_id,
                $item->variation,
                $item->quantity
            );
        }
    }
} 