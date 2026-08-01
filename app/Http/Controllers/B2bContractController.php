<?php

namespace App\Http\Controllers;

use App\Models\B2bContract;
use App\Models\B2bContractItem;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class B2bContractController extends Controller
{
    public function index()
    {
        $contracts = B2bContract::with('customer')->latest()->paginate(20);
        return view('b2b_contracts.index', compact('contracts'));
    }

    public function create()
    {
        return view('b2b_contracts.create', [
            'customers' => Customer::whereIn('customer_type', ['wholesale', 'contractor', 'project', 'government'])->get(),
            'items' => Item::orderBy('name_ar')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'credit_terms_days' => 'required|integer|min:0',
            'contract_credit_limit' => 'nullable|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required_with:items|exists:items,id',
            'items.*.contract_price' => 'required_with:items|numeric|min:0',
        ]);

        $contract = DB::transaction(function () use ($data) {
            $contract = B2bContract::create([
                'contract_number' => 'B2B-' . now()->format('Ymd') . '-' . str_pad((string) (B2bContract::count() + 1), 4, '0', STR_PAD_LEFT),
                'customer_id' => $data['customer_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'credit_terms_days' => $data['credit_terms_days'],
                'contract_credit_limit' => $data['contract_credit_limit'] ?? null,
                'status' => 'active',
            ]);

            foreach ($data['items'] ?? [] as $row) {
                B2bContractItem::create([
                    'b2b_contract_id' => $contract->id,
                    'item_id' => $row['item_id'],
                    'contract_price' => $row['contract_price'],
                ]);
            }

            return $contract;
        });

        return redirect()->route('b2b-contracts.index')->with('success', __('messages.b2b_contract.created'));
    }
}
