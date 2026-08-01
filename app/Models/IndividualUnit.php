<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndividualUnit extends Model
{
    protected $fillable = [
        'unit_code', 'qr_code', 'item_id', 'nursery_id', 'location_id',
        'height_cm', 'crown_diameter_cm', 'age', 'price', 'lifecycle_status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function statusHistories()
    {
        return $this->morphMany(StatusHistory::class, 'trackable');
    }
}
