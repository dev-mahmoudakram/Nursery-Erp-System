<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CustomerPortalUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['customer_id', 'name', 'email', 'password', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['is_active' => 'boolean'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
