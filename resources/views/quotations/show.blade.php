@extends('layouts.app')
@section('title', $quotation->quotation_number)
@section('content')
<h4 class="mb-3">{{ __('messages.quotation.number') }} {{ $quotation->quotation_number }} — {{ $quotation->customer->name_ar }}</h4>

<div class="row mb-4">
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.quotation.subtotal') }}</small><h5>{{ $quotation->subtotal }}</h5></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.quotation.total') }}</small><h5>{{ $quotation->total }}</h5></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.quotation.margin') }}</small><h5>{{ $quotation->margin_percent }}%</h5></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.quotation.status') }}</small><h5><span class="badge bg-secondary">{{ $quotation->status }}</span></h5></div></div>
</div>

<div class="card mb-4">
    <table class="table mb-0">
        <thead class="table-light">
            <tr><th>{{ __('messages.quotation.batch') }}</th><th>{{ __('messages.quotation.quantity') }}</th><th>{{ __('messages.quotation.unit_price') }}</th><th>{{ __('messages.quotation.subtotal') }}</th></tr>
        </thead>
        <tbody>
        @foreach($quotation->items as $item)
            <tr>
                <td>{{ $item->batch->batch_number }} — {{ $item->batch->item->name_ar }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->unit_price }}</td>
                <td>{{ $item->subtotal }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@if(in_array($quotation->status, ['draft', 'sent']))
    <form action="{{ route('quotations.accept', $quotation) }}" method="POST" onsubmit="return confirm('{{ __('messages.quotation.accept') }}?')">
        @csrf
        <button class="btn btn-success">{{ __('messages.quotation.accept') }}</button>
    </form>
@endif
@endsection
