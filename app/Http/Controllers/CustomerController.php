<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::when($request->q, fn ($q) => $q->where('name_ar', 'like', "%{$request->q}%")
            ->orWhere('customer_code', 'like', "%{$request->q}%"))
            ->latest()->paginate(20);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_code' => 'required|string|unique:customers,customer_code',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'customer_type' => 'required|in:retail,wholesale,contractor,project,government',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'city' => 'nullable|string',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', __('messages.customer.created'));
    }

    public function show(Customer $customer)
    {
        $customer->load('opportunities', 'quotations', 'salesOrders', 'invoices.payments');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'customer_type' => 'required|in:retail,wholesale,contractor,project,government',
            'credit_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,blocked',
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', __('messages.customer.updated'));
    }
}
