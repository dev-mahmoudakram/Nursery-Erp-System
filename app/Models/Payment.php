<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['invoice_id', 'amount', 'method', 'reference_number', 'received_by', 'paid_at'];

    protected $casts = ['paid_at' => 'datetime'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
