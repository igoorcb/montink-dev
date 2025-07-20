<?php

namespace App\Infrastructure\Services;

use App\Domain\Entities\Order;
use App\Domain\Services\EmailServiceInterface;
use Illuminate\Support\Facades\Mail;

class EmailService implements EmailServiceInterface
{
    public function sendOrderConfirmation(Order $order): void
    {
        Mail::send('emails.order-confirmation', [
            'order' => $order
        ], function ($message) use ($order) {
            $message->to($order->customer_email, $order->customer_name)
                   ->subject('Confirmação do Pedido #' . $order->order_number);
        });
    }

    public function sendOrderStatusUpdate(Order $order, string $newStatus): void
    {
        Mail::send('emails.order-status-update', [
            'order' => $order,
            'newStatus' => $newStatus
        ], function ($message) use ($order, $newStatus) {
            $message->to($order->customer_email, $order->customer_name)
                   ->subject('Atualização do Pedido #' . $order->order_number . ' - ' . ucfirst($newStatus));
        });
    }

    public function sendOrderCancellation(Order $order): void
    {
        Mail::send('emails.order-cancellation', [
            'order' => $order
        ], function ($message) use ($order) {
            $message->to($order->customer_email, $order->customer_name)
                   ->subject('Cancelamento do Pedido #' . $order->order_number);
        });
    }
} 