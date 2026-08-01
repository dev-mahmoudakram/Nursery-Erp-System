<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\InventoryMovement;
use App\Models\Item;
use App\Models\Location;
use App\Models\Nursery;
use App\Models\StatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::with('item', 'nursery', 'location')
            ->when($request->nursery_id, fn ($q) => $q->where('nursery_id', $request->nursery_id))
            ->latest()->paginate(20);

        $nurseries = Nursery::orderBy('name_ar')->get();

        return view('batches.index', compact('batches', 'nurseries'));
    }

    public function create()
    {
        return view('batches.create', [
            'items' => Item::orderBy('name_ar')->get(),
            'nurseries' => Nursery::orderBy('name_ar')->get(),
            'locations' => Location::orderBy('code')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'batch_number' => 'required|string|unique:batches,batch_number',
            'item_id' => 'required|exists:items,id',
            'nursery_id' => 'required|exists:nurseries,id',
            'location_id' => 'nullable|exists:locations,id',
            'production_date' => 'nullable|date',
            'quantity' => 'required|numeric|min:0',
            'size' => 'nullable|string',
            'quality_grade' => 'required|in:A,B,C',
            'image' => 'nullable|image|max:4096',
        ]);

        $data['image_path'] = $request->file('image')?->store('batches', 'public');
        unset($data['image']);

        DB::transaction(function () use ($data) {
            $batch = Batch::create($data + ['lifecycle_status' => 'new_production']);

            // رصيد افتتاحي كحركة مخزون (بند ٥.٥)
            InventoryMovement::create([
                'batch_id' => $batch->id,
                'movement_type' => 'opening_balance',
                'to_location_id' => $batch->location_id,
                'quantity' => $batch->quantity,
                'user_id' => auth()->id(),
                'movement_date' => now(),
            ]);
        });

        return redirect()->route('batches.index')->with('success', __('messages.batch.created'));
    }

    /**
     * تغيير حالة دورة حياة الدفعة مع تسجيل السجل (status_histories) إلزاميًا.
     */
    public function changeStatus(Request $request, Batch $batch)
    {
        $request->validate(['to_status' => 'required|string', 'notes' => 'nullable|string']);

        DB::transaction(function () use ($request, $batch) {
            $from = $batch->lifecycle_status;
            $batch->update(['lifecycle_status' => $request->to_status]);

            StatusHistory::create([
                'trackable_type' => Batch::class,
                'trackable_id' => $batch->id,
                'from_status' => $from,
                'to_status' => $request->to_status,
                'user_id' => auth()->id(),
                'notes' => $request->notes,
                'changed_at' => now(),
            ]);
        });

        return back()->with('success', __('messages.batch.status_updated'));
    }

    public function show(Batch $batch)
    {
        $batch->load('item', 'nursery', 'location', 'movements.user', 'statusHistories.user');
        return view('batches.show', compact('batch'));
    }

    /**
     * تحديث صورة الدفعة لاحقًا (أثناء الفحص/الجرد الميداني) - مستقل عن تغيير الحالة.
     */
    public function uploadPhoto(Request $request, Batch $batch)
    {
        $request->validate(['image' => 'required|image|max:4096']);

        $batch->update(['image_path' => $request->file('image')->store('batches', 'public')]);

        return back()->with('success', __('messages.batch.photo_updated'));
    }
}
