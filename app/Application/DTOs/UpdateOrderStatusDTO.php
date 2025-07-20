<?php

namespace App\Application\DTOs;

class UpdateOrderStatusDTO
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $status
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            orderId: (int) $data['order_id'],
            status: $data['status']
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'status' => $this->status
        ];
    }
} 