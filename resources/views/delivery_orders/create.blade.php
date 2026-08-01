@extends('layouts.app')
@section('title', __('messages.delivery.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.delivery.add') }} — {{ $order->order_number }} ({{ $order->customer->name_ar }})</h4>
<div class="card p-4">
<form action="{{ route('delivery-orders.store') }}" method="POST">
    @csrf
    <input type="hidden" name="sales_order_id" value="{{ $order->id }}">
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.delivery.vehicle') }}</label>
            <select name="vehicle_id" class="form-select">
                <option value="">—</option>
                @foreach($vehicles as $v)<option value="{{ $v->id }}">{{ $v->plate_number }} ({{ $v->type }})</option>@endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.delivery.driver') }}</label>
            <select name="driver_id" class="form-select">
                <option value="">—</option>
                @foreach($drivers as $dr)<option value="{{ $dr->id }}">{{ $dr->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.delivery.scheduled_date') }}</label>
            <input type="date" name="scheduled_date" class="form-control">
        </div>
    </div>

    <h6>{{ __('messages.delivery.item') }} / {{ __('messages.delivery.quantity') }}</h6>
    <table class="table">
        <thead class="table-light"><tr><th></th><th>{{ __('messages.delivery.item') }}</th><th>{{ __('messages.order.remaining_quantity') }}</th><th>{{ __('messages.delivery.quantity') }}</th></tr></thead>
        <tbody>
        @foreach($order->items as $item)
            @if($item->remaining_quantity > 0)
            <tr>
                <td><input type="checkbox" checked onchange="document.getElementById('qty{{ $item->id }}').disabled = !this.checked"></td>
                <td>{{ $item->batch->batch_number }} — {{ $item->batch->item->name_ar }}</td>
                <td>{{ $item->remaining_quantity }}</td>
                <td>
                    <input type="hidden" name="items[{{ $loop->index }}][sales_order_item_id]" value="{{ $item->id }}">
                    <input type="number" step="0.01" id="qty{{ $item->id }}" name="items[{{ $loop->index }}][quantity]"
                           class="form-control" value="{{ $item->remaining_quantity }}" max="{{ $item->remaining_quantity }}">
                </td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <button class="btn btn-success mt-3">{{ __('messages.common.save') }}</button>
    <a href="{{ route('sales-orders.show', $order) }}" class="btn btn-light mt-3">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@if ($errors->any())
    <div class="alert alert-danger mt-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
@endsection
