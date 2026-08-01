@extends('layouts.app')
@section('title', $customer->name_ar)
@section('content')
<h4 class="mb-3">{{ $customer->name_ar }} ({{ $customer->customer_code }})</h4>
<div class="row mb-4">
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.customer.credit_limit') }}</small><h4>{{ $customer->credit_limit }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.customer.current_balance') }}</small><h4>{{ $customer->current_balance }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3 text-center bg-success text-white"><small>{{ __('messages.customer.available_credit') }}</small><h4>{{ $customer->available_credit }}</h4></div></div>
</div>

<div class="card mb-4">
    <div class="card-header">{{ __('messages.quotation.title') }}</div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>{{ __('messages.quotation.number') }}</th><th>{{ __('messages.quotation.status') }}</th><th>{{ __('messages.quotation.total') }}</th></tr></thead>
        <tbody>
        @forelse($customer->quotations as $q)
            <tr><td><a href="{{ route('quotations.show', $q) }}">{{ $q->quotation_number }}</a></td><td>{{ $q->status }}</td><td>{{ $q->total }}</td></tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted">—</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">{{ __('messages.invoice.title') }}</div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>{{ __('messages.invoice.number') }}</th><th>{{ __('messages.invoice.status') }}</th><th>{{ __('messages.invoice.remaining') }}</th></tr></thead>
        <tbody>
        @forelse($customer->invoices as $inv)
            <tr><td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->invoice_number }}</a></td><td>{{ $inv->status }}</td><td>{{ $inv->remaining }}</td></tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted">—</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
