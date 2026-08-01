<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderDocument extends Model
{
    protected $fillable = ['government_tender_id', 'name', 'file_path', 'uploaded_by'];

    public function tender()
    {
        return $this->belongsTo(GovernmentTender::class, 'government_tender_id');
    }
}
