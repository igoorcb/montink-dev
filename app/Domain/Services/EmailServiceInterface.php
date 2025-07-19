<?php

namespace App\Domain\Services;

use App\Domain\Entities\Order;

interface EmailServiceInterface
{
    public function sendOrderConfirmation(Order $order): void;
    public function sendOrderStatusUpdate(Order $order, string $newStatus): void;
    public function sendOrderCancellation(Order $order): void;
} 