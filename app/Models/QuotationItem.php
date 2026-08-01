<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = ['quotation_id', 'batch_id', 'quantity', 'unit_price', 'unit_cost', 'subtotal'];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
