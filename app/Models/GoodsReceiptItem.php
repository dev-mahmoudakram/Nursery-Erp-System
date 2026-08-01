<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptItem extends Model
{
    protected $fillable = [
        'goods_receipt_id', 'purchase_order_item_id', 'batch_id',
        'quantity_received', 'quality_ok', 'notes',
    ];

    protected $casts = ['quality_ok' => 'boolean'];

    public function receipt()
    {
        return $this->belongsTo(GoodsReceipt::class, 'goods_receipt_id');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
