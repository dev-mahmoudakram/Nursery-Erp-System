<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOpportunity extends Model
{
    protected $fillable = [
        'customer_id', 'title', 'expected_value', 'probability', 'stage',
        'lost_reason', 'sales_rep_id', 'expected_close_date',
    ];

    protected $casts = ['expected_close_date' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
