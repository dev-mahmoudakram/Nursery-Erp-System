<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(20);
        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('order.items.batch.item', 'customer', 'payments');
        return view('invoices.show', compact('invoice'));
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:cash,bank_transfer,card,cheque,online',
            'reference_number' => 'nullable|string',
        ]);

        abort_if($request->amount > $invoice->remaining, 422, 'المبلغ أكبر من المتبقي على الفاتورة');

        $invoice->recordPayment($request->amount, $request->method, $request->reference_number, auth()->id());

        return back()->with('success', __('messages.invoice.payment_recorded'));
    }
}
