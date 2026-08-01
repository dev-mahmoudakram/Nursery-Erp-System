@extends('layouts.app')
@section('title', $order->order_number)
@section('content')
<h4 class="mb-3">{{ $order->order_number }} — {{ $order->customer_name }}
    <span class="badge bg-{{ $order->status==='confirmed'?'success':($order->status==='cancelled'?'danger':'secondary') }}">{{ $order->status }}</span>
</h4>
<p class="text-muted">
    {{ __('messages.storefront.phone') }}: {{ $order->phone }} |
    {{ __('messages.storefront.nursery') }}: {{ $order->nursery->name_ar }} |
    {{ __('messages.storefront.delivery_method') }}: {{ $order->delivery_method }} |
    {{ __('messages.storefront.payment_method') }}: {{ $order->payment_method }} ({{ $order->payment_status }})
</p>

<div class="card mb-4">
    <table class="table mb-0">
        <thead class="table-light"><tr><th>{{ __('messages.item.title') }}</th><th>{{ __('messages.storefront.quantity') }}</th><th>{{ __('messages.storefront.price') }}</th><th>{{ __('messages.storefront.subtotal') }}</th></tr></thead>
        <tbody>
        @foreach($order->items as $i)
            <tr><td>{{ $i->item->name_ar }}</td><td>{{ $i->quantity }}</td><td>{{ $i->unit_price }}</td><td>{{ $i->subtotal }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="row mb-4">
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.quotation.subtotal') }}</small><h5>{{ $order->subtotal }}</h5></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>Discount</small><h5>{{ $order->discount_amount }}</h5></div></div>
    <div class="col-md-3"><div class="card p-3 text-center bg-success text-white"><small>{{ __('messages.storefront.total') }}</small><h5>{{ $order->total }}</h5></div></div>
</div>

@if($order->status === 'pending_review')
    <form action="{{ route('storefront-admin.orders.confirm', $order) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-success">{{ __('messages.storefront.confirm_order') }}</button>
    </form>
    <form action="{{ route('storefront-admin.orders.cancel', $order) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-outline-danger">{{ __('messages.storefront.cancel_order') }}</button>
    </form>
@elseif($order->salesOrder)
    <a href="{{ route('sales-orders.show', $order->salesOrder) }}" class="btn btn-outline-primary">{{ __('messages.order.title') }}: {{ $order->salesOrder->order_number }}</a>
@endif
@endsection
