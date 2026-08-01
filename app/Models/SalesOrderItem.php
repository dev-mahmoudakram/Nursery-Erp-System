<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    protected $fillable = ['sales_order_id', 'batch_id', 'quantity', 'unit_price', 'subtotal', 'delivered_quantity'];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function getRemainingQuantityAttribute(): float
    {
        return max(0, $this->quantity - $this->delivered_quantity);
    }
}
