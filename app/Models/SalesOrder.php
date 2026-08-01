<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesOrder extends Model
{
    protected $fillable = [
        'order_number', 'quotation_id', 'customer_id', 'status', 'total',
        'created_by', 'delivered_at', 'delivery_proof_path',
    ];

    protected $casts = ['delivered_at' => 'datetime'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * تسليم الطلب (كليًا أو جزئيًا). هذه هي اللحظة الوحيدة التي يُخصم فيها
     * المخزون نهائيًا (quantity وreserved_quantity معًا) — تطبيقًا لمبدأ
     * "لا خصم نهائي إلا بعد إثبات التسليم الفعلي" (BP-04 خطوة 6).
     */
    public function markItemDelivered(SalesOrderItem $item, float $deliveredQty): void
    {
        DB::transaction(function () use ($item, $deliveredQty) {
            $batch = $item->batch()->lockForUpdate()->first();

            $batch->decrement('quantity', $deliveredQty);
            $batch->decrement('reserved_quantity', $deliveredQty);

            InventoryMovement::create([
                'batch_id' => $batch->id,
                'movement_type' => 'deliver',
                'quantity' => $deliveredQty,
                'notes' => "تسليم لأمر بيع رقم {$this->order_number}",
                'movement_date' => now(),
            ]);

            $item->increment('delivered_quantity', $deliveredQty);

            $allDelivered = $this->items()->whereColumn('delivered_quantity', '<', 'quantity')->doesntExist();
            $anyDelivered = $this->items()->where('delivered_quantity', '>', 0)->exists();

            $this->update([
                'status' => $allDelivered ? 'delivered' : ($anyDelivered ? 'partially_delivered' : $this->status),
                'delivered_at' => $allDelivered ? now() : $this->delivered_at,
            ]);
        });
    }
}
