<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $orders = SalesOrder::with('customer')->latest()->paginate(20);
        return view('sales_orders.index', compact('orders'));
    }

    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load('items.batch.item', 'customer', 'invoice');
        return view('sales_orders.show', ['order' => $salesOrder]);
    }

    /**
     * تسجيل تسليم بند من الطلب (كليًا أو جزئيًا) — هنا فقط يُخصم المخزون نهائيًا.
     */
    public function deliverItem(Request $request, SalesOrder $salesOrder, SalesOrderItem $item)
    {
        $request->validate(['delivered_quantity' => 'required|numeric|min:0.01']);

        abort_unless($item->sales_order_id === $salesOrder->id, 404);
        abort_if($request->delivered_quantity > $item->remaining_quantity, 422, 'الكمية أكبر من المتبقي للتسليم');

        $salesOrder->markItemDelivered($item, $request->delivered_quantity);

        return back()->with('success', __('messages.order.delivery_recorded'));
    }

    /**
     * إصدار فاتورة للطلب (يدويًا من المحاسبة، عادة بعد التسليم الكامل أو الجزئي حسب سياسة الشركة).
     */
    public function generateInvoice(SalesOrder $salesOrder)
    {
        abort_if($salesOrder->invoice()->exists(), 422, 'يوجد فاتورة بالفعل لهذا الطلب');

        $invoice = Invoice::createFromOrder($salesOrder);

        return redirect()->route('invoices.show', $invoice)->with('success', __('messages.invoice.created'));
    }
}
