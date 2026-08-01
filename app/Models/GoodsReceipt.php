<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GoodsReceipt extends Model
{
    protected $fillable = ['purchase_order_id', 'received_by', 'received_at', 'notes'];

    protected $casts = ['received_at' => 'datetime'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function receiptItems()
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * تسجيل استلام فعلي لبند أو أكثر من أمر الشراء:
     * - يُنشئ دفعة (Batch) جديدة تلقائيًا لكل بند مُستلَم بجودة سليمة، مرتبطة بمشتل الوجهة.
     * - يسجل حركة مخزون 'receive' (رصيد وارد من مورد خارجي، وليس رصيد افتتاحي داخلي).
     * - يحدّث received_quantity على بند أمر الشراء ويُحدّث حالة الأمر إجمالًا.
     * تطبيقًا لـ BR-PUR-01 وربط تكلفة الشراء بالمخزون.
     */
    public static function receive(PurchaseOrder $po, array $lines, ?int $receivedBy, ?string $notes = null): self
    {
        return DB::transaction(function () use ($po, $lines, $receivedBy, $notes) {
            $receipt = self::create([
                'purchase_order_id' => $po->id,
                'received_by' => $receivedBy,
                'received_at' => now(),
                'notes' => $notes,
            ]);

            foreach ($lines as $line) {
                $poItem = PurchaseOrderItem::with('item')->findOrFail($line['purchase_order_item_id']);
                $qty = (float) $line['quantity_received'];
                $qualityOk = (bool) ($line['quality_ok'] ?? true);

                $batch = null;

                if ($qualityOk && $qty > 0) {
                    $batch = Batch::create([
                        'batch_number' => 'PO-' . $po->po_number . '-' . Str::upper(Str::random(4)),
                        'item_id' => $poItem->item_id,
                        'nursery_id' => $po->destination_nursery_id,
                        'quantity' => $qty,
                        'quality_grade' => $poItem->item->quality_grade ?? 'A',
                        'lifecycle_status' => 'new_production',
                        'production_date' => now()->toDateString(),
                    ]);

                    InventoryMovement::create([
                        'batch_id' => $batch->id,
                        'movement_type' => 'receive',
                        'quantity' => $qty,
                        'notes' => "استلام من أمر شراء رقم {$po->po_number} - المورد #{$po->supplier_id}",
                        'movement_date' => now(),
                    ]);
                }

                GoodsReceiptItem::create([
                    'goods_receipt_id' => $receipt->id,
                    'purchase_order_item_id' => $poItem->id,
                    'batch_id' => $batch?->id,
                    'quantity_received' => $qty,
                    'quality_ok' => $qualityOk,
                    'notes' => $line['notes'] ?? null,
                ]);

                // الكمية المرفوضة (quality_ok=false) تُحتسب كمُستلمة إداريًا (أُغلق البند) لكن دون إدخال للمخزون
                $poItem->increment('received_quantity', $qty);
            }

            $po->refreshStatusFromReceipts();

            return $receipt;
        });
    }
}
