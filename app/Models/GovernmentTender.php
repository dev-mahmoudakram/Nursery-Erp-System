<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentTender extends Model
{
    protected $fillable = [
        'tender_number', 'title', 'government_entity_name', 'customer_id', 'quotation_id',
        'announcement_date', 'submission_deadline', 'tender_document_fee', 'bid_bond_amount',
        'estimated_value', 'status', 'outcome_reason', 'notes',
    ];

    protected $casts = [
        'announcement_date' => 'date',
        'submission_deadline' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function evaluations()
    {
        return $this->hasMany(TenderEvaluation::class);
    }

    public function documents()
    {
        return $this->hasMany(TenderDocument::class);
    }

    public function latestEvaluation()
    {
        return $this->hasOne(TenderEvaluation::class)->latestOfMany();
    }
}
