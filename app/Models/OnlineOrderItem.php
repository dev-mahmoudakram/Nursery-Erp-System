<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineOrderItem extends Model
{
    protected $fillable = ['online_order_id', 'item_id', 'quantity', 'unit_price', 'subtotal'];

    public function order()
    {
        return $this->belongsTo(OnlineOrder::class, 'online_order_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
