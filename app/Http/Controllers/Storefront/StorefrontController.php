<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Nursery;
use App\Models\OnlineOrder;
use App\Models\OnlineOrderItem;
use App\Models\StorefrontProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    private const CART_COOKIE = 'storefront_cart_token';

    /**
     * يعرض فقط الأصناف المعتمدة صراحة للنشر العام (BR-B2C-04)، ويُخفي أي صنف
     * نفد مخزونه الحي فعليًا (وليس مجرد رقم ثابت قديم).
     */
    public function index(Request $request)
    {
        $products = StorefrontProduct::with('item')
            ->where('is_published', true)
            ->get()
            ->filter(fn ($p) => $p->live_stock > 0)
            ->when($request->q, fn ($c) => $c->filter(fn ($p) => str_contains($p->item->name_ar, $request->q)));

        return view('storefront.index', compact('products'));
    }

    public function show(StorefrontProduct $product)
    {
        abort_unless($product->is_published, 404);
        $product->load('item');
        $reviews = $product->reviews()->where('is_approved', true)->latest()->get();
        $nurseries = Nursery::where('is_active', true)->get();

        return view('storefront.show', compact('product', 'reviews', 'nurseries'));
    }

    private function getOrCreateCart(Request $request): Cart
    {
        $token = $request->cookie(self::CART_COOKIE) ?? Str::uuid()->toString();

        return Cart::firstOrCreate(['session_token' => $token]);
    }

    public function addToCart(Request $request, StorefrontProduct $product)
    {
        $request->validate(['quantity' => 'required|numeric|min:0.01']);

        $cart = $this->getOrCreateCart($request);

        $cartItem = CartItem::firstOrNew(['cart_id' => $cart->id, 'item_id' => $product->item_id]);
        $cartItem->quantity = ($cartItem->quantity ?? 0) + $request->quantity;
        $cartItem->save();

        return redirect()->route('storefront.cart')
            ->withCookie(cookie(self::CART_COOKIE, $cart->session_token, 60 * 24 * 30))
            ->with('success', __('messages.storefront.added_to_cart'));
    }

    public function cart(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load('items.item');

        return view('storefront.cart', compact('cart'))
            ->withCookie(cookie(self::CART_COOKIE, $cart->session_token, 60 * 24 * 30));
    }

    public function removeFromCart(Request $request, CartItem $cartItem)
    {
        $cartItem->delete();
        return back();
    }

    public function checkout(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load('items.item');
        abort_if($cart->items->isEmpty(), 422, 'السلة فارغة');

        return view('storefront.checkout', [
            'cart' => $cart,
            'nurseries' => Nursery::where('is_active', true)->get(),
        ]);
    }

    /**
     * إتمام الطلب: طلب ضيف كامل بدون حساب مسبق (BR-B2C-02)، مع كوبون خصم اختياري.
     * لا يُنشئ حجزًا حقيقيًا في المخزون بعد — ذلك يحدث فقط عند تأكيد الموظف
     * (OnlineOrder::convertToInternalOrder) لضمان مراجعة بشرية قبل الالتزام بالمخزون.
     */
    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email',
            'city' => 'nullable|string',
            'nursery_id' => 'required|exists:nurseries,id',
            'delivery_method' => 'required|in:delivery,pickup',
            'delivery_address' => 'required_if:delivery_method,delivery|nullable|string',
            'requested_delivery_date' => 'nullable|date|after_or_equal:today',
            'payment_method' => 'required|in:online,cash_on_delivery',
            'coupon_code' => 'nullable|string',
        ]);

        $cart = $this->getOrCreateCart($request);
        $cart->load('items.item');
        abort_if($cart->items->isEmpty(), 422, 'السلة فارغة');

        $order = DB::transaction(function () use ($data, $cart) {
            $subtotal = $cart->items->sum(fn ($i) => $i->quantity * $i->item->retail_price);

            $coupon = null;
            $discount = 0;
            if (! empty($data['coupon_code'])) {
                $coupon = Coupon::where('code', $data['coupon_code'])->first();
                if ($coupon && $coupon->isValidNow()) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $coupon->increment('used_count');
                } else {
                    $coupon = null;
                }
            }

            $order = OnlineOrder::create($data + [
                'order_number' => 'B2C-' . now()->format('Ymd') . '-' . str_pad((string) (OnlineOrder::count() + 1), 4, '0', STR_PAD_LEFT),
                'coupon_id' => $coupon?->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total' => $subtotal - $discount,
                'payment_status' => $data['payment_method'] === 'online' ? 'paid' : 'pending',
                'status' => 'pending_review',
            ]);

            foreach ($cart->items as $cartItem) {
                OnlineOrderItem::create([
                    'online_order_id' => $order->id,
                    'item_id' => $cartItem->item_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->item->retail_price,
                    'subtotal' => $cartItem->quantity * $cartItem->item->retail_price,
                ]);
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('storefront.confirmation', $order)
            ->withCookie(cookie()->forget(self::CART_COOKIE));
    }

    public function confirmation(OnlineOrder $order)
    {
        return view('storefront.confirmation', compact('order'));
    }
}
