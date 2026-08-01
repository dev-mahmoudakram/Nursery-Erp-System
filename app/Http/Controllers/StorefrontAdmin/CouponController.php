<?php

namespace App\Http\Controllers\StorefrontAdmin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('storefront_admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('storefront_admin.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        Coupon::create($data);

        return redirect()->route('storefront-admin.coupons.index')->with('success', __('messages.storefront.coupon_created'));
    }
}
