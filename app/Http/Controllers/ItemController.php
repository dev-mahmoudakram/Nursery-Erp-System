<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::with('mainCategory', 'subCategory')
            ->when($request->q, fn ($q) => $q->where('name_ar', 'like', "%{$request->q}%")
                ->orWhere('item_code', 'like', "%{$request->q}%"))
            ->latest()->paginate(20);

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = ItemCategory::whereNull('parent_id')->with('children')->get();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_code' => 'required|string|unique:items,item_code',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'main_category_id' => 'nullable|exists:item_categories,id',
            'sub_category_id' => 'nullable|exists:item_categories,id',
            'plant_type' => 'nullable|string',
            'pot_size' => 'nullable|string',
            'height_cm' => 'nullable|numeric',
            'crown_diameter_cm' => 'nullable|numeric',
            'quality_grade' => 'required|in:A,B,C',
            'unit_of_measure' => 'nullable|string',
            'safety_stock' => 'nullable|numeric',
            'production_cost' => 'nullable|numeric',
            'retail_price' => 'nullable|numeric',
            'wholesale_price' => 'nullable|numeric',
            'contractor_price' => 'nullable|numeric',
            'project_price' => 'nullable|numeric',
            'government_price' => 'nullable|numeric',
            'clearance_price' => 'nullable|numeric',
            'min_order_qty' => 'nullable|numeric',
            'image' => 'nullable|image|max:4096', // 4MB
        ]);

        $data['image_path'] = $request->file('image')?->store('items', 'public');
        unset($data['image']);

        // منع تكرار الصنف (بند ٥.٢): unique على item_code كفى، ويمكن إضافة تحقق إضافي بالاسم العلمي عند الحاجة
        Item::create($data);

        return redirect()->route('items.index')->with('success', __('messages.item.created'));
    }

    public function edit(Item $item)
    {
        $categories = ItemCategory::whereNull('parent_id')->with('children')->get();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'item_code' => 'required|string|unique:items,item_code,' . $item->id,
            'name_ar' => 'required|string|max:255',
            'quality_grade' => 'required|in:A,B,C',
            'safety_stock' => 'nullable|numeric',
            'retail_price' => 'nullable|numeric',
            'wholesale_price' => 'nullable|numeric',
            'contractor_price' => 'nullable|numeric',
            'project_price' => 'nullable|numeric',
            'government_price' => 'nullable|numeric',
            'clearance_price' => 'nullable|numeric',
            'image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }
        unset($data['image']);

        $item->update($data);

        return redirect()->route('items.index')->with('success', __('messages.item.updated'));
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return back()->with('success', __('messages.item.deleted'));
    }
}
