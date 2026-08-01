<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Models\InventoryMovement;
use App\Models\StatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BatchApiController extends Controller
{
    public function index(Request $request)
    {
        $batches = Batch::with('item')
            ->when($request->nursery_id, fn ($q) => $q->where('nursery_id', $request->nursery_id))
            ->paginate(50);

        return BatchResource::collection($batches);
    }

    public function show(Batch $batch)
    {
        return new BatchResource($batch);
    }

    /**
     * بحث سريع عن دفعة برمز QR (يُستخدم من تطبيق الجوال أثناء المسح).
     */
    public function findByQr(string $qr)
    {
        $batch = Batch::with('item')->where('qr_code', $qr)->firstOrFail();
        return new BatchResource($batch);
    }

    /**
     * نقطة نهاية مزامنة حركات المخزون المُلتقطة أثناء انقطاع الاتصال (BR-INV-05 / FR-015).
     * يقبل مصفوفة حركات، كل واحدة بمعرف client_uuid فريد لضمان عدم التكرار (Idempotent).
     */
    public function syncMovements(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movements' => 'required|array|min:1',
            'movements.*.client_uuid' => 'required|uuid',
            'movements.*.batch_id' => 'required|exists:batches,id',
            'movements.*.movement_type' => 'required|string',
            'movements.*.quantity' => 'required|numeric|min:0',
            'movements.*.counted_quantity' => 'nullable|numeric',
            'movements.*.movement_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $results = [];

        DB::transaction(function () use ($request, &$results) {
            foreach ($request->input('movements') as $m) {
                // idempotency: تجاهل أي حركة سبقت مزامنتها بنفس client_uuid
                $existing = InventoryMovement::where('client_uuid', $m['client_uuid'])->first();
                if ($existing) {
                    $results[] = ['client_uuid' => $m['client_uuid'], 'status' => 'already_synced', 'id' => $existing->id];
                    continue;
                }

                $movement = InventoryMovement::create([
                    'client_uuid' => $m['client_uuid'],
                    'batch_id' => $m['batch_id'],
                    'movement_type' => $m['movement_type'],
                    'from_location_id' => $m['from_location_id'] ?? null,
                    'to_location_id' => $m['to_location_id'] ?? null,
                    'quantity' => $m['quantity'],
                    'counted_quantity' => $m['counted_quantity'] ?? null,
                    'quantity_diff' => isset($m['counted_quantity'])
                        ? $m['counted_quantity'] - $m['quantity'] : null,
                    'diff_reason' => $m['diff_reason'] ?? null,
                    'user_id' => $request->user()?->id,
                    'notes' => $m['notes'] ?? null,
                    'movement_date' => $m['movement_date'],
                ]);

                $results[] = ['client_uuid' => $m['client_uuid'], 'status' => 'synced', 'id' => $movement->id];
            }
        });

        return response()->json(['results' => $results]);
    }

    public function changeStatus(Request $request, Batch $batch)
    {
        $request->validate(['to_status' => 'required|string']);

        DB::transaction(function () use ($request, $batch) {
            $from = $batch->lifecycle_status;
            $batch->update(['lifecycle_status' => $request->to_status]);

            StatusHistory::create([
                'trackable_type' => Batch::class,
                'trackable_id' => $batch->id,
                'from_status' => $from,
                'to_status' => $request->to_status,
                'user_id' => $request->user()?->id,
                'notes' => $request->notes,
                'changed_at' => now(),
            ]);
        });

        return new BatchResource($batch->fresh());
    }
}
