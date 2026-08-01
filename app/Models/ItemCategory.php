<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $fillable = ['parent_id', 'name_ar', 'name_en'];

    public function parent()
    {
        return $this->belongsTo(ItemCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ItemCategory::class, 'parent_id');
    }
}
