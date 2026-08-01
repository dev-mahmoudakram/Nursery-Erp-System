<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = ['item_id', 'customer_name', 'rating', 'comment', 'is_approved'];

    protected $casts = ['is_approved' => 'boolean'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
