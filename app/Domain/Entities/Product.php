<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'variations',
        'active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'variations' => 'array',
        'active' => 'boolean'
    ];

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->inventory->sum('quantity');
    }

    public function hasStock(int $quantity, ?string $variation = null): bool
    {
        $inventory = $this->inventory;
        
        if ($variation) {
            $inventory = $inventory->where('variation', $variation);
        }
        
        return $inventory->sum('quantity') >= $quantity;
    }
} 