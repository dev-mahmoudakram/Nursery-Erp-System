<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'valid_from', 'valid_until',
        'usage_limit', 'used_count', 'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date', 'valid_until' => 'date', 'is_active' => 'boolean',
    ];

    public function isValidNow(): bool
    {
        if (! $this->is_active) {
            return false;
        }
        if ($this->valid_from && now()->lt($this->valid_from)) {
            return false;
        }
        if ($this->valid_until && now()->gt($this->valid_until)) {
            return false;
        }
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percent') {
            return round($subtotal * ($this->discount_value / 100), 2);
        }

        return min($this->discount_value, $subtotal);
    }
}
