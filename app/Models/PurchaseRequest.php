<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $fillable = [
        'request_number', 'item_id', 'nursery_id', 'quantity', 'status',
        'requested_by', 'approved_by', 'notes',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }
}
