<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemApiController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::where('status', 'active')
            ->when($request->q, fn ($q) => $q->where('name_ar', 'like', "%{$request->q}%")
                ->orWhere('item_code', 'like', "%{$request->q}%"))
            ->paginate(50);

        return ItemResource::collection($items);
    }

    public function show(Item $item)
    {
        return new ItemResource($item);
    }
}
