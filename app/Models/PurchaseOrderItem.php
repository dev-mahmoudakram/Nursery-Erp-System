<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = ['purchase_order_id', 'item_id', 'quantity', 'unit_cost', 'subtotal', 'received_quantity'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getRemainingQuantityAttribute(): float
    {
        return max(0, $this->quantity - $this->received_quantity);
    }
}
