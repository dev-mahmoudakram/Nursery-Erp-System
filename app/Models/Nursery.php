<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nursery extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name_ar', 'name_en', 'city', 'address', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
