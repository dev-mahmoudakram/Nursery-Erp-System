<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['nursery_id', 'parent_location_id', 'code', 'type', 'status_type', 'name'];

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_location_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_location_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
