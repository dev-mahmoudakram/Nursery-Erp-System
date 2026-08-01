<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'client_uuid', 'batch_id', 'individual_unit_id', 'movement_type',
        'from_location_id', 'to_location_id', 'quantity',
        'counted_quantity', 'quantity_diff', 'diff_reason',
        'user_id', 'notes', 'photo_path', 'movement_date',
    ];

    protected $casts = ['movement_date' => 'datetime'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function individualUnit()
    {
        return $this->belongsTo(IndividualUnit::class);
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
