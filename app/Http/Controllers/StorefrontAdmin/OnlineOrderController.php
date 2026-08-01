<?php

namespace App\Http\Controllers\StorefrontAdmin;

use App\Http\Controllers\Controller;
use App\Models\OnlineOrder;
use Illuminate\Http\Request;

class OnlineOrderController extends Controller
{
    public function index()
    {
        $orders = OnlineOrder::with('nursery')->latest()->paginate(20);
        return view('storefront_admin.orders.index', compact('orders'));
    }

    public function show(OnlineOrder $order)
    {
        $order->load('items.item', 'nursery', 'coupon', 'salesOrder');
        return view('storefront_admin.orders.show', compact('order'));
    }

    /**
     * تأكيد الطلب من الموظف: يحوّله فعليًا لعرض سعر وأمر بيع داخليين حقيقيين
     * عبر OnlineOrder::convertToInternalOrder — نفس محرك المخزون والمبيعات.
     */
    public function confirm(OnlineOrder $order)
    {
        abort_if($order->status !== 'pending_review', 422, 'الطلب ليس في حالة قابلة للتأكيد');

        $order->convertToInternalOrder(auth()->id());

        return back()->with('success', __('messages.storefront.order_confirmed'));
    }

    public function cancel(Request $request, OnlineOrder $order)
    {
        $order->update(['status' => 'cancelled']);
        return back()->with('success', __('messages.storefront.order_cancelled'));
    }
}
