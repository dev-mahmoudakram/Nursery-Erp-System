<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DeliveryOrder extends Model
{
    protected $fillable = [
        'delivery_number', 'sales_order_id', 'vehicle_id', 'driver_id', 'status',
        'scheduled_date', 'delivery_cost', 'route_notes', 'signed_by_name',
        'proof_photo_path', 'proof_signature_path', 'failure_reason', 'delivered_at',
    ];

    protected $casts = ['scheduled_date' => 'date', 'delivered_at' => 'datetime'];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }

    /**
     * إثبات التسليم الفعلي (BR-LOG-02): يخصم المخزون نهائيًا عبر
     * SalesOrder::markItemDelivered لكل بند مشمول بهذا التسليم، ويحرر المركبة والسائق.
     */
    public function confirmDelivery(string $signedBy, ?string $photoPath, ?string $signaturePath): void
    {
        DB::transaction(function () use ($signedBy, $photoPath, $signaturePath) {
            foreach ($this->items as $deliveryItem) {
                $this->salesOrder->markItemDelivered($deliveryItem->salesOrderItem, $deliveryItem->quantity);
            }

            $this->update([
                'status' => 'delivered',
                'signed_by_name' => $signedBy,
                'proof_photo_path' => $photoPath,
                'proof_signature_path' => $signaturePath,
                'delivered_at' => now(),
            ]);

            $this->vehicle()->update(['status' => 'available']);
            $this->driver()->update(['status' => 'available']);
        });
    }

    /**
     * تسجيل فشل التسليم أو رفض/نقص لدى العميل (BR-LOG-02).
     * لا يُخصم أي مخزون هنا — الكمية تبقى محجوزة على أمر البيع حتى إعادة الجدولة.
     */
    public function markFailed(string $reason): void
    {
        $this->update(['status' => 'failed', 'failure_reason' => $reason]);
        $this->vehicle()->update(['status' => 'available']);
        $this->driver()->update(['status' => 'available']);
    }
}
