@extends('layouts.app')
@section('title', $order->order_number)
@section('content')
<h4 class="mb-3">{{ __('messages.order.number') }} {{ $order->order_number }} — {{ $order->customer->name_ar }}
    <span class="badge bg-secondary">{{ $order->status }}</span>
</h4>

<a href="{{ route('delivery-orders.create', ['sales_order_id' => $order->id]) }}" class="btn btn-sm btn-outline-primary mb-3">🚚 {{ __('messages.delivery.add') }}</a>


<div class="card mb-4">
    <table class="table mb-0">
        <thead class="table-light">
            <tr>
                <th>{{ __('messages.quotation.batch') }}</th><th>{{ __('messages.quotation.quantity') }}</th>
                <th>{{ __('messages.order.delivered_quantity') }}</th><th>{{ __('messages.order.remaining_quantity') }}</th><th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->batch->batch_number }} — {{ $item->batch->item->name_ar }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->delivered_quantity }}</td>
                <td>{{ $item->remaining_quantity }}</td>
                <td>
                    @if($item->remaining_quantity > 0)
                        <form action="{{ route('sales-orders.deliver-item', [$order, $item]) }}" method="POST" class="d-flex gap-1">
                            @csrf
                            <input type="number" step="0.01" name="delivered_quantity" class="form-control form-control-sm" style="width:100px" max="{{ $item->remaining_quantity }}" required>
                            <button class="btn btn-sm btn-primary">{{ __('messages.order.deliver') }}</button>
                        </form>
                    @else
                        <span class="text-success">✓</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@if($order->invoice)
    <a href="{{ route('invoices.show', $order->invoice) }}" class="btn btn-outline-secondary">{{ __('messages.invoice.title') }}: {{ $order->invoice->invoice_number }}</a>
@else
    <form action="{{ route('sales-orders.generate-invoice', $order) }}" method="POST">
        @csrf
        <button class="btn btn-success">{{ __('messages.order.generate_invoice') }}</button>
    </form>
@endif
@endsection
