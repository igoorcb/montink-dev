<?php

namespace App\Application\DTOs;

class CreateProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly ?string $description,
        public readonly array $stock,
        public readonly array $variations = []
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            price: (float) $data['price'],
            description: $data['description'] ?? null,
            stock: $data['stock'] ?? [],
            variations: $data['variations'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'stock' => $this->stock,
            'variations' => $this->variations
        ];
    }
} 