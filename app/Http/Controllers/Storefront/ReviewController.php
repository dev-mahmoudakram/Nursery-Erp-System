<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\StorefrontProduct;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * أي تقييم يُقدَّم من الجمهور يبقى غير معروض (is_approved=false) حتى تراجعه
     * الإدارة، لمنع محتوى مسيء أو غير موثوق من الظهور مباشرة.
     */
    public function store(Request $request, StorefrontProduct $product)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        ProductReview::create($data + ['item_id' => $product->item_id, 'is_approved' => false]);

        return back()->with('success', __('messages.storefront.review_submitted'));
    }
}
