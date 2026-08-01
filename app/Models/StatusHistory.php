<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['trackable_type', 'trackable_id', 'from_status', 'to_status', 'user_id', 'notes', 'changed_at'];

    protected $casts = ['changed_at' => 'datetime'];

    public function trackable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
