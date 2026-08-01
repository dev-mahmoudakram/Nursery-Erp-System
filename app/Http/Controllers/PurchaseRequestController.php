<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Nursery;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::with('item', 'nursery')->latest()->paginate(20);
        return view('purchase_requests.index', compact('requests'));
    }

    public function create()
    {
        return view('purchase_requests.create', [
            'items' => Item::orderBy('name_ar')->get(),
            'nurseries' => Nursery::orderBy('name_ar')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_id' => 'required|exists:items,id',
            'nursery_id' => 'required|exists:nurseries,id',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        PurchaseRequest::create($data + [
            'request_number' => 'PR-' . now()->format('Ymd') . '-' . str_pad((string) (PurchaseRequest::count() + 1), 4, '0', STR_PAD_LEFT),
            'status' => 'pending',
            'requested_by' => auth()->id(),
        ]);

        return redirect()->route('purchase-requests.index')->with('success', __('messages.purchase_request.created'));
    }

    public function approve(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        return back()->with('success', __('messages.purchase_request.approved'));
    }

    public function reject(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->update(['status' => 'rejected', 'approved_by' => auth()->id()]);
        return back()->with('success', __('messages.purchase_request.rejected'));
    }
}
