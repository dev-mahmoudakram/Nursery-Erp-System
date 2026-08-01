<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number', 'item_id', 'nursery_id', 'location_id',
        'production_date', 'quantity', 'reserved_quantity', 'damaged_quantity',
        'isolated_quantity', 'size', 'quality_grade', 'lifecycle_status',
        'qr_code', 'image_path', 'last_inventory_date', 'expected_ready_date',
    ];

    protected $casts = [
        'production_date' => 'date',
        'last_inventory_date' => 'date',
        'expected_ready_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function statusHistories()
    {
        return $this->morphMany(StatusHistory::class, 'trackable');
    }

    /**
     * قاعدة العمل من كراسة الطلب (بند ٥.٥):
     * الكمية المتاحة للبيع = الجاهزة − المحجوز − التالف − المعزول − مخزون الأمان − تحت الفحص
     * ملاحظة: "تحت الفحص" و"مخزون الأمان" على مستوى الصنف/الدفعة بحسب حالة lifecycle_status
     * وقيمة safety_stock الخاصة بالصنف الأب.
     */
    public function getAvailableQuantityAttribute(): float
    {
        $underInspection = $this->lifecycle_status === 'under_inspection' ? $this->quantity : 0;
        $safetyStock = $this->item?->safety_stock ?? 0;

        $available = $this->quantity
            - $this->reserved_quantity
            - $this->damaged_quantity
            - $this->isolated_quantity
            - $safetyStock
            - $underInspection;

        return max(0, $available);
    }
}
