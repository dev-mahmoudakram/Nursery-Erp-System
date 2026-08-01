<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code', 'name_ar', 'name_en', 'customer_type', 'phone', 'email',
        'city', 'credit_limit', 'current_balance', 'sales_rep_id', 'status',
    ];

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function opportunities()
    {
        return $this->hasMany(SalesOpportunity::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function b2bContracts()
    {
        return $this->hasMany(B2bContract::class);
    }

    public function portalUsers()
    {
        return $this->hasMany(CustomerPortalUser::class);
    }

    public function activeContract()
    {
        return $this->b2bContracts()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->first();
    }

    /**
     * الرصيد المتاح من حد الائتمان (BR-B2B-01 / تنبيه تجاوز حد الائتمان في BR-ALT-01).
     */
    public function getAvailableCreditAttribute(): float
    {
        return max(0, $this->credit_limit - $this->current_balance);
    }
}
