<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'variation',
        'quantity',
        'min_quantity'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_quantity' => 'integer'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->min_quantity;
    }

    public function decreaseStock(int $quantity): void
    {
        if ($this->quantity >= $quantity) {
            $this->quantity -= $quantity;
            $this->save();
        }
    }

    public function increaseStock(int $quantity): void
    {
        $this->quantity += $quantity;
        $this->save();
    }
} 