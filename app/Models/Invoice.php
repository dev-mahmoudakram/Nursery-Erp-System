<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'sales_order_id', 'customer_id', 'total',
        'paid_amount', 'status', 'issue_date', 'due_date',
    ];

    protected $casts = ['issue_date' => 'date', 'due_date' => 'date'];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingAttribute(): float
    {
        return max(0, $this->total - $this->paid_amount);
    }

    /**
     * إنشاء فاتورة من أمر بيع (يُستدعى عادة عند التسليم الكامل أو الجزئي حسب سياسة الشركة).
     */
    public static function createFromOrder(SalesOrder $order, int $dueInDays = 30): self
    {
        $invoice = self::create([
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . str_pad((string) (self::count() + 1), 4, '0', STR_PAD_LEFT),
            'sales_order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'total' => $order->total,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays($dueInDays)->toDateString(),
        ]);

        $order->customer()->increment('current_balance', $order->total);

        return $invoice;
    }

    /**
     * تسجيل دفعة وتحديث حالة الفاتورة ورصيد العميل (BR-CRM-05: متابعة التحصيل).
     */
    public function recordPayment(float $amount, string $method, ?string $reference, ?int $receivedBy): Payment
    {
        return DB::transaction(function () use ($amount, $method, $reference, $receivedBy) {
            $payment = Payment::create([
                'invoice_id' => $this->id,
                'amount' => $amount,
                'method' => $method,
                'reference_number' => $reference,
                'received_by' => $receivedBy,
                'paid_at' => now(),
            ]);

            $this->increment('paid_amount', $amount);
            $this->update([
                'status' => $this->paid_amount >= $this->total ? 'paid' : 'partially_paid',
            ]);

            $this->customer()->decrement('current_balance', $amount);

            return $payment;
        });
    }
}
