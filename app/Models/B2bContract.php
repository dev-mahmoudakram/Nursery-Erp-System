<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bContract extends Model
{
    protected $fillable = [
        'contract_number', 'customer_id', 'start_date', 'end_date',
        'credit_terms_days', 'contract_credit_limit', 'status', 'notes',
    ];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(B2bContractItem::class);
    }

    public function isCurrentlyActive(): bool
    {
        return $this->status === 'active'
            && $this->start_date->lte(now())
            && $this->end_date->gte(now());
    }

    /**
     * السعر التعاقدي لصنف مُحدَّد إن وُجد، وإلا null (يتراجع الطلب لقوائم الأسعار القياسية).
     * BR-B2B-01: قوائم أسعار تعاقدية تتفوق على القوائم الست القياسية.
     */
    public function contractPriceFor(int $itemId): ?float
    {
        return $this->items()->where('item_id', $itemId)->value('contract_price');
    }

    /**
     * حد الائتمان الفعلي المطبَّق: التعاقدي إن وُجد، وإلا حد ائتمان العميل العام.
     */
    public function effectiveCreditLimit(): float
    {
        return $this->contract_credit_limit ?? $this->customer->credit_limit;
    }
}
