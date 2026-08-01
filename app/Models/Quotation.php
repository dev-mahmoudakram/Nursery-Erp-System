<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number', 'customer_id', 'sales_opportunity_id', 'customer_type_snapshot',
        'status', 'valid_until', 'subtotal', 'discount_total', 'total', 'margin_percent',
        'created_by', 'approved_by',
    ];

    protected $casts = ['valid_until' => 'date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function opportunity()
    {
        return $this->belongsTo(SalesOpportunity::class, 'sales_opportunity_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function salesOrder()
    {
        return $this->hasOne(SalesOrder::class);
    }

    /**
     * حجز كميات كل بند في العرض (BR-CRM-04 / FR-031).
     * يُستدعى عند الإنشاء وعند إرسال العرض للعميل.
     */
    public function reserveStock(): void
    {
        DB::transaction(function () {
            foreach ($this->items as $item) {
                $batch = $item->batch()->lockForUpdate()->first();
                $batch->increment('reserved_quantity', $item->quantity);

                InventoryMovement::create([
                    'batch_id' => $batch->id,
                    'movement_type' => 'reserve',
                    'quantity' => $item->quantity,
                    'notes' => "حجز لعرض سعر رقم {$this->quotation_number}",
                    'movement_date' => now(),
                ]);
            }
        });
    }

    /**
     * فك الحجز عند انتهاء صلاحية العرض دون قبول، أو عند إلغائه (Scheduled Job — انظر
     * App\Console\Commands\UnreserveExpiredQuotations).
     */
    public function unreserveStock(): void
    {
        DB::transaction(function () {
            foreach ($this->items as $item) {
                $batch = $item->batch()->lockForUpdate()->first();
                $batch->decrement('reserved_quantity', $item->quantity);

                InventoryMovement::create([
                    'batch_id' => $batch->id,
                    'movement_type' => 'unreserve',
                    'quantity' => $item->quantity,
                    'notes' => "فك حجز — انتهاء صلاحية عرض سعر رقم {$this->quotation_number}",
                    'movement_date' => now(),
                ]);
            }
        });
    }

    /**
     * تحويل العرض إلى أمر بيع (بند BP-04 خطوة 5). الحجز يبقى قائمًا وينتقل
     * منطقيًا لأمر البيع حتى التسليم الفعلي، حيث يُخصم نهائيًا من المخزون.
     */
    public function convertToOrder(?int $createdBy = null): SalesOrder
    {
        return DB::transaction(function () use ($createdBy) {
            $order = SalesOrder::create([
                'order_number' => 'SO-' . now()->format('Ymd') . '-' . str_pad((string) (SalesOrder::count() + 1), 4, '0', STR_PAD_LEFT),
                'quotation_id' => $this->id,
                'customer_id' => $this->customer_id,
                'status' => 'confirmed',
                'total' => $this->total,
                'created_by' => $createdBy,
            ]);

            foreach ($this->items as $item) {
                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'batch_id' => $item->batch_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ]);
            }

            $this->update(['status' => 'converted']);

            return $order;
        });
    }

    /**
     * إعادة احتساب المجاميع وهامش الربح من البنود الحالية (BR-PRC-02).
     */
    public function recalculateTotals(): void
    {
        $subtotal = $this->items->sum('subtotal');
        $totalCost = $this->items->sum(fn ($i) => $i->unit_cost * $i->quantity);

        $this->subtotal = $subtotal;
        $this->total = $subtotal - $this->discount_total;
        $this->margin_percent = $subtotal > 0 ? round((($subtotal - $totalCost) / $subtotal) * 100, 2) : null;
        $this->save();
    }
}
