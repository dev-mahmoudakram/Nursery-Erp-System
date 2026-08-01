<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseryTransferItem extends Model
{
    protected $fillable = [
        'nursery_transfer_id', 'batch_id', 'quantity_sent',
        'quantity_received', 'quantity_damaged_in_transit',
    ];

    public function transfer()
    {
        return $this->belongsTo(NurseryTransfer::class, 'nursery_transfer_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
