<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\SalesOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('customer')->latest()->paginate(20);
        return view('quotations.index', compact('quotations'));
    }

    public function create(Request $request)
    {
        return view('quotations.create', [
            'customers' => Customer::where('status', 'active')->orderBy('name_ar')->get(),
            'batches' => Batch::with('item')->where('lifecycle_status', 'ready_for_sale')->get(),
            'opportunityId' => $request->opportunity_id,
        ]);
    }

    /**
     * إنشاء عرض سعر: يحسب السعر تلقائيًا حسب نوع العميل، يتحقق من الكمية المتاحة،
     * يحجز الكمية فورًا، ويحسب هامش الربح (BR-CRM-03/04, BR-PRC-02, FR-031/032).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_opportunity_id' => 'nullable|exists:sales_opportunities,id',
            'valid_until' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.batch_id' => 'required|exists:batches,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $customer = Customer::findOrFail($data['customer_id']);

        $quotation = DB::transaction(function () use ($data, $customer) {
            $quotation = Quotation::create([
                'quotation_number' => 'QT-' . now()->format('Ymd') . '-' . str_pad((string) (Quotation::count() + 1), 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'sales_opportunity_id' => $data['sales_opportunity_id'] ?? null,
                'customer_type_snapshot' => $customer->customer_type,
                'status' => 'draft',
                'valid_until' => $data['valid_until'],
                'created_by' => auth()->id(),
            ]);

            foreach ($data['items'] as $row) {
                $batch = Batch::with('item')->findOrFail($row['batch_id']);

                // التحقق من الكمية المتاحة فعليًا قبل الحجز
                if ($row['quantity'] > $batch->available_quantity) {
                    abort(422, "الكمية المطلوبة من دفعة {$batch->batch_number} أكبر من المتاح ({$batch->available_quantity})");
                }

                $unitPrice = $customer->activeContract()?->contractPriceFor($batch->item_id)
                    ?? $batch->item->priceFor($customer->customer_type)
                    ?? $batch->item->retail_price;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'batch_id' => $batch->id,
                    'quantity' => $row['quantity'],
                    'unit_price' => $unitPrice,
                    'unit_cost' => $batch->item->production_cost,
                    'subtotal' => $unitPrice * $row['quantity'],
                ]);
            }

            $quotation->load('items');
            $quotation->recalculateTotals();
            $quotation->reserveStock();

            if ($quotation->sales_opportunity_id) {
                SalesOpportunity::where('id', $quotation->sales_opportunity_id)->update(['stage' => 'quotation_sent']);
            }

            return $quotation;
        });

        return redirect()->route('quotations.show', $quotation)->with('success', __('messages.quotation.created'));
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('items.batch.item', 'customer', 'opportunity');
        return view('quotations.show', compact('quotation'));
    }

    /**
     * قبول العميل للعرض ثم تحويله فورًا إلى أمر بيع (BP-04 خطوات 5-6).
     */
    public function accept(Quotation $quotation)
    {
        abort_if($quotation->status !== 'draft' && $quotation->status !== 'sent', 422, 'لا يمكن قبول هذا العرض في حالته الحالية');

        $quotation->update(['status' => 'accepted']);

        // فحص حد الائتمان الفعلي (التعاقدي إن وُجد، وإلا حد العميل العام) قبل تأكيد الطلب - BR-B2B-01 / BR-ALT-01
        $customer = $quotation->customer;
        $contract = $customer->activeContract();
        $effectiveLimit = $contract ? $contract->effectiveCreditLimit() : $customer->credit_limit;

        if ($effectiveLimit > 0 && ($customer->current_balance + $quotation->total) > $effectiveLimit) {
            abort(422, "تجاوز حد الائتمان: الرصيد الحالي {$customer->current_balance} + قيمة الطلب {$quotation->total} يتجاوز الحد المسموح {$effectiveLimit}");
        }

        $order = $quotation->convertToOrder(auth()->id());

        if ($quotation->sales_opportunity_id) {
            SalesOpportunity::where('id', $quotation->sales_opportunity_id)->update(['stage' => 'won']);
        }

        return redirect()->route('sales-orders.show', $order)->with('success', __('messages.quotation.converted'));
    }
}
