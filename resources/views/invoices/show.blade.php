@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('content')
<h4 class="mb-3">{{ __('messages.invoice.number') }} {{ $invoice->invoice_number }} — {{ $invoice->customer->name_ar }}</h4>

<div class="row mb-4">
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.invoice.total') }}</small><h5>{{ $invoice->total }}</h5></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.invoice.paid') }}</small><h5>{{ $invoice->paid_amount }}</h5></div></div>
    <div class="col-md-3"><div class="card p-3 text-center bg-warning"><small>{{ __('messages.invoice.remaining') }}</small><h5>{{ $invoice->remaining }}</h5></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.invoice.status') }}</small><h5><span class="badge bg-secondary">{{ $invoice->status }}</span></h5></div></div>
</div>

@if($invoice->remaining > 0)
<div class="card p-4 mb-4">
    <h6>{{ __('messages.invoice.record_payment') }}</h6>
    <form action="{{ route('invoices.record-payment', $invoice) }}" method="POST" class="row g-2">
        @csrf
        <div class="col-md-3"><input type="number" step="0.01" name="amount" class="form-control" placeholder="{{ __('messages.invoice.amount') }}" max="{{ $invoice->remaining }}" required></div>
        <div class="col-md-3">
            <select name="method" class="form-select">
                <option value="cash">cash</option><option value="bank_transfer">bank_transfer</option>
                <option value="card">card</option><option value="cheque">cheque</option><option value="online">online</option>
            </select>
        </div>
        <div class="col-md-3"><input name="reference_number" class="form-control" placeholder="Ref #"></div>
        <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('messages.invoice.record_payment') }}</button></div>
    </form>
</div>
@endif

<div class="card">
    <div class="card-header">{{ __('messages.invoice.record_payment') }} — Log</div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>{{ __('messages.invoice.amount') }}</th><th>{{ __('messages.invoice.method') }}</th><th>Ref</th><th>Date</th></tr></thead>
        <tbody>
        @forelse($invoice->payments as $p)
            <tr><td>{{ $p->amount }}</td><td>{{ $p->method }}</td><td>{{ $p->reference_number }}</td><td>{{ $p->paid_at }}</td></tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted">—</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
