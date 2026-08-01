<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PortalController extends Controller
{
    /**
     * لوحة العميل: عرض أوامر البيع والفواتير الخاصة به فقط (BR-B2B-02).
     */
    public function dashboard()
    {
        $customer = Auth::guard('customer')->user()->customer;

        $orders = SalesOrder::where('customer_id', $customer->id)->latest()->paginate(10);
        $invoices = $customer->invoices()->latest()->paginate(10, ['*'], 'invoices_page');

        return view('portal.dashboard', compact('customer', 'orders', 'invoices'));
    }

    /**
     * إعادة الطلب: ينسخ بنود أمر بيع سابق إلى عرض سعر جديد (مسودة) بنفس الأسعار
     * التعاقدية/القياسية الحالية للعميل — لا يحجز تلقائيًا حتى يراجعها العميل (BR-B2B-02).
     */
    public function reorder(SalesOrder $order)
    {
        $customer = Auth::guard('customer')->user()->customer;
        abort_unless($order->customer_id === $customer->id, 403);

        $quotation = DB::transaction(function () use ($order, $customer) {
            $quotation = Quotation::create([
                'quotation_number' => 'QT-' . now()->format('Ymd') . '-' . str_pad((string) (Quotation::count() + 1), 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'customer_type_snapshot' => $customer->customer_type,
                'status' => 'draft',
                'valid_until' => now()->addDays(7),
            ]);

            foreach ($order->items as $orderItem) {
                $contractPrice = $customer->activeContract()?->contractPriceFor($orderItem->batch->item_id);
                $unitPrice = $contractPrice ?? $orderItem->batch->item->priceFor($customer->customer_type) ?? $orderItem->unit_price;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'batch_id' => $orderItem->batch_id,
                    'quantity' => $orderItem->quantity,
                    'unit_price' => $unitPrice,
                    'unit_cost' => $orderItem->batch->item->production_cost,
                    'subtotal' => $unitPrice * $orderItem->quantity,
                ]);
            }

            $quotation->load('items');
            $quotation->recalculateTotals();

            return $quotation;
        });

        return redirect()->route('portal.dashboard')->with('success', __('messages.portal.reorder_created', ['number' => $quotation->quotation_number]));
    }
}
