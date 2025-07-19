<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'min_amount',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'active' => 'boolean'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(): bool
    {
        if (!$this->active) {
            return false;
        }

        $now = Carbon::now();
        if ($now < $this->valid_from || $now > $this->valid_until) {
            return false;
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function canBeAppliedTo(decimal $subtotal): bool
    {
        return $this->isValid() && $subtotal >= $this->min_amount;
    }

    public function calculateDiscount(decimal $subtotal): decimal
    {
        if ($this->type === 'percentage') {
            return $subtotal * ($this->value / 100);
        }

        return $this->value;
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
} 