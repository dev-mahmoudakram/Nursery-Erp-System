<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseryTransfer extends Model
{
    protected $fillable = [
        'transfer_number', 'from_nursery_id', 'to_nursery_id', 'status',
        'requested_by', 'approved_by', 'received_by', 'notes',
    ];

    public function fromNursery()
    {
        return $this->belongsTo(Nursery::class, 'from_nursery_id');
    }

    public function toNursery()
    {
        return $this->belongsTo(Nursery::class, 'to_nursery_id');
    }

    public function items()
    {
        return $this->hasMany(NurseryTransferItem::class);
    }

    /**
     * دورة التحويل الكاملة (بند ٥.٧ من كراسة الطلب):
     * requested -> approved -> preparing -> in_transit -> received -> inspected -> closed
     * لا يُخصم نهائيًا من المرسل ولا يُضاف نهائيًا للمستلم إلا بعد "received" + "inspected".
     */
    public function canTransitionTo(string $next): bool
    {
        $flow = ['requested', 'approved', 'preparing', 'in_transit', 'received', 'inspected', 'closed'];
        $current = array_search($this->status, $flow);
        $target = array_search($next, $flow);

        return $current !== false && $target !== false && $target === $current + 1;
    }
}
