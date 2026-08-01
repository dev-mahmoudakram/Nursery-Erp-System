<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Models\Driver;
use App\Models\SalesOrder;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        $deliveries = DeliveryOrder::with('salesOrder.customer', 'vehicle', 'driver')->latest()->paginate(20);
        return view('delivery_orders.index', compact('deliveries'));
    }

    public function create(Request $request)
    {
        $order = SalesOrder::with('items.batch.item')->findOrFail($request->sales_order_id);

        return view('delivery_orders.create', [
            'order' => $order,
            'vehicles' => Vehicle::where('status', 'available')->get(),
            'drivers' => Driver::where('status', 'available')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'scheduled_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.sales_order_item_id' => 'required|exists:sales_order_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $delivery = DB::transaction(function () use ($data) {
            $delivery = DeliveryOrder::create([
                'delivery_number' => 'DEL-' . now()->format('Ymd') . '-' . str_pad((string) (DeliveryOrder::count() + 1), 4, '0', STR_PAD_LEFT),
                'sales_order_id' => $data['sales_order_id'],
                'vehicle_id' => $data['vehicle_id'] ?? null,
                'driver_id' => $data['driver_id'] ?? null,
                'status' => 'pending',
                'scheduled_date' => $data['scheduled_date'] ?? null,
            ]);

            foreach ($data['items'] as $row) {
                DeliveryOrderItem::create([
                    'delivery_order_id' => $delivery->id,
                    'sales_order_item_id' => $row['sales_order_item_id'],
                    'quantity' => $row['quantity'],
                ]);
            }

            if ($delivery->vehicle_id) {
                Vehicle::where('id', $delivery->vehicle_id)->update(['status' => 'in_use']);
            }
            if ($delivery->driver_id) {
                Driver::where('id', $delivery->driver_id)->update(['status' => 'on_route']);
            }

            return $delivery;
        });

        return redirect()->route('delivery-orders.show', $delivery)->with('success', __('messages.delivery.created'));
    }

    public function show(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('items.salesOrderItem.batch.item', 'salesOrder.customer', 'vehicle', 'driver');
        return view('delivery_orders.show', ['delivery' => $deliveryOrder]);
    }

    public function confirm(Request $request, DeliveryOrder $deliveryOrder)
    {
        $request->validate([
            'signed_by_name' => 'required|string|max:255',
            'proof_photo' => 'nullable|image|max:5120',       // 5MB
            'proof_signature' => 'nullable|image|max:2048',   // 2MB
        ]);

        $photoPath = $request->file('proof_photo')?->store('delivery-proofs/photos', 'public');
        $signaturePath = $request->file('proof_signature')?->store('delivery-proofs/signatures', 'public');

        $deliveryOrder->confirmDelivery($request->signed_by_name, $photoPath, $signaturePath);

        return back()->with('success', __('messages.delivery.confirmed'));
    }

    public function fail(Request $request, DeliveryOrder $deliveryOrder)
    {
        $request->validate(['failure_reason' => 'required|string|max:500']);

        $deliveryOrder->markFailed($request->failure_reason);

        return back()->with('success', __('messages.delivery.marked_failed'));
    }
}
