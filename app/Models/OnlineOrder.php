<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OnlineOrder extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'phone', 'email', 'city',
        'nursery_id', 'delivery_method', 'delivery_address', 'requested_delivery_date',
        'coupon_id', 'subtotal', 'discount_amount', 'total',
        'payment_method', 'payment_status', 'status',
        'quotation_id', 'sales_order_id', 'customer_id',
    ];

    protected $casts = ['requested_delivery_date' => 'date'];

    public function items()
    {
        return $this->hasMany(OnlineOrderItem::class);
    }

    public function nursery()
    {
        return $this->belongsTo(Nursery::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    /**
     * نقطة التكامل الأهم في موديول B2C: تحويل طلب متجر عام (Guest) إلى دورة العمل
     * الداخلية الحقيقية بالكامل — نفس محرك CRM المبني مسبقًا وليس منطقًا موازيًا:
     *   1) إيجاد/إنشاء عميل تجزئة (retail) مطابق برقم الجوال.
     *   2) تخصيص كل بند لدفعة/دفعات فعلية في مشتل الوجهة (FIFO حسب تاريخ الإنتاج).
     *   3) إنشاء عرض سعر وحجز الكميات فورًا (نفس Quotation::reserveStock()).
     *   4) تحويله مباشرة لأمر بيع (نفس Quotation::convertToOrder()).
     * بهذا يمر طلب المتجر بكل ضوابط الجودة والمخزون المطبَّقة على بقية القنوات، دون استثناء.
     */
    public function convertToInternalOrder(?int $staffUserId = null): void
    {
        DB::transaction(function () use ($staffUserId) {
            $customer = Customer::firstOrCreate(
                ['phone' => $this->phone],
                [
                    'customer_code' => 'B2C-' . str_pad((string) (Customer::count() + 1), 5, '0', STR_PAD_LEFT),
                    'name_ar' => $this->customer_name,
                    'customer_type' => 'retail',
                    'email' => $this->email,
                    'city' => $this->city,
                ]
            );

            $quotation = Quotation::create([
                'quotation_number' => 'QT-B2C-' . now()->format('Ymd') . '-' . str_pad((string) (Quotation::count() + 1), 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'customer_type_snapshot' => 'retail',
                'status' => 'draft',
                'valid_until' => now()->addDays(1),
                'created_by' => $staffUserId,
            ]);

            foreach ($this->items as $onlineItem) {
                $remaining = $onlineItem->quantity;

                $batches = Batch::where('item_id', $onlineItem->item_id)
                    ->where('nursery_id', $this->nursery_id)
                    ->where('lifecycle_status', 'ready_for_sale')
                    ->orderBy('production_date')
                    ->get();

                foreach ($batches as $batch) {
                    if ($remaining <= 0) {
                        break;
                    }
                    $take = min($remaining, $batch->available_quantity);
                    if ($take <= 0) {
                        continue;
                    }

                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'batch_id' => $batch->id,
                        'quantity' => $take,
                        'unit_price' => $onlineItem->unit_price,
                        'unit_cost' => $batch->item->production_cost,
                        'subtotal' => $onlineItem->unit_price * $take,
                    ]);

                    $remaining -= $take;
                }

                if ($remaining > 0) {
                    // لا يوجد مخزون كافٍ فعليًا وقت التأكيد رغم توفره وقت الطلب — يتطلب تدخل الموظف
                    abort(422, "لا يوجد مخزون كافٍ حاليًا لأحد الأصناف بمشتل التوريد المحدَّد. راجع الطلب يدويًا.");
                }
            }

            $quotation->load('items');
            $quotation->recalculateTotals();
            $quotation->reserveStock();

            $order = $quotation->convertToOrder($staffUserId);

            $this->update([
                'customer_id' => $customer->id,
                'quotation_id' => $quotation->id,
                'sales_order_id' => $order->id,
                'status' => 'confirmed',
            ]);
        });
    }
}
