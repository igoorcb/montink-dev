<?php

namespace App\Application\DTOs;

class CreateOrderDTO
{
    public function __construct(
        public readonly string $customerName,
        public readonly string $customerEmail,
        public readonly ?string $customerPhone,
        public readonly string $address,
        public readonly string $number,
        public readonly ?string $complement,
        public readonly string $neighborhood,
        public readonly string $city,
        public readonly string $state,
        public readonly string $cep
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            customerName: $data['customer_name'],
            customerEmail: $data['customer_email'],
            customerPhone: $data['customer_phone'] ?? null,
            address: $data['address'],
            number: $data['number'],
            complement: $data['complement'] ?? null,
            neighborhood: $data['neighborhood'],
            city: $data['city'],
            state: $data['state'],
            cep: $data['cep']
        );
    }

    public function toArray(): array
    {
        return [
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'address' => $this->address,
            'number' => $this->number,
            'complement' => $this->complement,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
            'cep' => $this->cep
        ];
    }
} 