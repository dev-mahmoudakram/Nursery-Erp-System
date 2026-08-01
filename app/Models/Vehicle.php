<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['plate_number', 'type', 'capacity', 'status'];

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }
}
