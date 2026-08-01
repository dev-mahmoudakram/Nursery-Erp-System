<?php

namespace App\Http\Controllers\StorefrontAdmin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StorefrontProduct;
use Illuminate\Http\Request;

class StorefrontProductController extends Controller
{
    public function index()
    {
        $products = StorefrontProduct::with('item')->latest()->paginate(20);
        $unpublishedItemsCount = Item::whereDoesntHave('storefrontProduct')->count();

        return view('storefront_admin.products.index', compact('products', 'unpublishedItemsCount'));
    }

    public function create()
    {
        $items = Item::whereDoesntHave('storefrontProduct')->orderBy('name_ar')->get();
        return view('storefront_admin.products.create', compact('items'));
    }

    /**
     * نشر صنف في المتجر العام - قرار صريح لكل صنف (BR-B2C-04)، وليس نشرًا تلقائيًا شاملاً.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id|unique:storefront_products,item_id',
            'display_name_ar' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'cover_image' => 'nullable|image|max:4096', // 4MB
        ]);

        $data['cover_image_path'] = $request->file('cover_image')?->store('storefront/covers', 'public');
        unset($data['cover_image']);

        StorefrontProduct::create($data + ['is_published' => true, 'published_by' => auth()->id()]);

        return redirect()->route('storefront-admin.products.index')->with('success', __('messages.storefront.product_published'));
    }

    public function togglePublish(StorefrontProduct $storefrontProduct)
    {
        $storefrontProduct->update(['is_published' => ! $storefrontProduct->is_published]);
        return back()->with('success', __('messages.storefront.status_updated'));
    }
}
