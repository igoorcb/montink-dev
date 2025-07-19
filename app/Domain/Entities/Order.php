<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'status',
        'subtotal',
        'shipping_cost',
        'discount',
        'total',
        'coupon_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zipcode',
        'shipping_country'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function updateStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function calculateShippingCost(): decimal
    {
        if ($this->subtotal >= 200.00) {
            return 0.00;
        }

        if ($this->subtotal >= 52.00 && $this->subtotal <= 166.59) {
            return 15.00;
        }

        return 20.00;
    }

    public function recalculateTotals(): void
    {
        $this->shipping_cost = $this->calculateShippingCost();
        $this->total = $this->subtotal + $this->shipping_cost - $this->discount;
        $this->save();
    }
} 