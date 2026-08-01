<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Item;
use App\Models\Nursery;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('supplier', 'destinationNursery')->latest()->paginate(20);
        return view('purchase_orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        return view('purchase_orders.create', [
            'suppliers' => Supplier::where('status', 'active')->orderBy('name_ar')->get(),
            'nurseries' => Nursery::orderBy('name_ar')->get(),
            'items' => Item::orderBy('name_ar')->get(),
            'purchaseRequestId' => $request->purchase_request_id,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'destination_nursery_id' => 'required|exists:nurseries,id',
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'expected_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $order = DB::transaction(function () use ($data) {
            $order = PurchaseOrder::create([
                'po_number' => 'PO-' . now()->format('Ymd') . '-' . str_pad((string) (PurchaseOrder::count() + 1), 4, '0', STR_PAD_LEFT),
                'supplier_id' => $data['supplier_id'],
                'destination_nursery_id' => $data['destination_nursery_id'],
                'purchase_request_id' => $data['purchase_request_id'] ?? null,
                'status' => 'sent',
                'expected_date' => $data['expected_date'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $total = 0;
            foreach ($data['items'] as $row) {
                $subtotal = $row['quantity'] * $row['unit_cost'];
                $total += $subtotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'item_id' => $row['item_id'],
                    'quantity' => $row['quantity'],
                    'unit_cost' => $row['unit_cost'],
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total' => $total]);

            if ($data['purchase_request_id'] ?? null) {
                PurchaseRequest::where('id', $data['purchase_request_id'])->update(['status' => 'converted']);
            }

            return $order;
        });

        return redirect()->route('purchase-orders.show', $order)->with('success', __('messages.purchase_order.created'));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items.item', 'supplier', 'destinationNursery', 'goodsReceipts.receiptItems.batch');
        return view('purchase_orders.show', ['order' => $purchaseOrder]);
    }

    /**
     * تسجيل استلام فعلي (كليًا أو جزئيًا) — يُنشئ دفعة مخزون تلقائيًا لكل بند بجودة سليمة (BR-PUR-01).
     */
    public function receiveGoods(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'lines' => 'required|array|min:1',
            'lines.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            'lines.*.quantity_received' => 'required|numeric|min:0',
            'lines.*.quality_ok' => 'nullable|boolean',
        ]);

        GoodsReceipt::receive($purchaseOrder, $request->lines, auth()->id(), $request->notes);

        return redirect()->route('purchase-orders.show', $purchaseOrder)->with('success', __('messages.purchase_order.received'));
    }
}
