<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number', 'supplier_id', 'destination_nursery_id', 'purchase_request_id',
        'status', 'total', 'created_by', 'expected_date',
    ];

    protected $casts = ['expected_date' => 'date'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function destinationNursery()
    {
        return $this->belongsTo(Nursery::class, 'destination_nursery_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    /**
     * تحديث حالة الأمر بعد كل استلام: مستلم بالكامل / جزئيًا.
     */
    public function refreshStatusFromReceipts(): void
    {
        $fullyReceived = $this->items()->whereColumn('received_quantity', '<', 'quantity')->doesntExist();
        $anyReceived = $this->items()->where('received_quantity', '>', 0)->exists();

        $this->update([
            'status' => $fullyReceived ? 'received' : ($anyReceived ? 'partially_received' : $this->status),
        ]);
    }
}
