<?php

namespace App\Http\Controllers\StorefrontAdmin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;

class ReviewModerationController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with('item')->where('is_approved', false)->latest()->paginate(20);
        return view('storefront_admin.reviews.index', compact('reviews'));
    }

    public function approve(ProductReview $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', __('messages.storefront.review_approved'));
    }

    public function reject(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', __('messages.storefront.review_rejected'));
    }
}
