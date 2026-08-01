<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesOpportunity;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index()
    {
        $opportunities = SalesOpportunity::with('customer')->latest()->paginate(20);
        return view('opportunities.index', compact('opportunities'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name_ar')->get();
        return view('opportunities.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'expected_value' => 'nullable|numeric',
            'probability' => 'nullable|integer|min:0|max:100',
            'expected_close_date' => 'nullable|date',
        ]);

        SalesOpportunity::create($data + ['stage' => 'target_customer']);

        return redirect()->route('opportunities.index')->with('success', __('messages.opportunity.created'));
    }

    /**
     * تحديث مرحلة الفرصة، مع إلزام سبب عند التعليم كـ 'خسارة' (BR-CRM-02 / FR-030).
     */
    public function updateStage(Request $request, SalesOpportunity $opportunity)
    {
        $request->validate([
            'stage' => 'required|in:target_customer,first_contact,needs_analysis,quotation_sent,negotiation,won,lost,postponed',
            'lost_reason' => 'required_if:stage,lost|nullable|string|max:500',
        ]);

        $opportunity->update([
            'stage' => $request->stage,
            'lost_reason' => $request->stage === 'lost' ? $request->lost_reason : $opportunity->lost_reason,
        ]);

        return back()->with('success', __('messages.opportunity.stage_updated'));
    }

    public function show(SalesOpportunity $opportunity)
    {
        $opportunity->load('customer', 'quotations');
        return view('opportunities.show', compact('opportunity'));
    }
}
