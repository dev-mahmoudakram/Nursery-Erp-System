@extends('layouts.app')
@section('title', __('messages.invoice.title'))
@section('content')
<h4 class="mb-3">{{ __('messages.invoice.title') }}</h4>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.invoice.number') }}</th><th>{{ __('messages.invoice.customer') }}</th><th>{{ __('messages.invoice.total') }}</th><th>{{ __('messages.invoice.remaining') }}</th><th>{{ __('messages.invoice.status') }}</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($invoices as $inv)
        <tr>
            <td>{{ $inv->invoice_number }}</td>
            <td>{{ $inv->customer->name_ar }}</td>
            <td>{{ $inv->total }}</td>
            <td>{{ $inv->remaining }}</td>
            <td><span class="badge bg-{{ $inv->status==='paid'?'success':($inv->status==='overdue'?'danger':'secondary') }}">{{ $inv->status }}</span></td>
            <td><a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.view') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $invoices->links() }}</div>
@endsection
