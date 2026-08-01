@extends('layouts.app')
@section('title', $delivery->delivery_number)
@section('content')
<h4 class="mb-3">{{ __('messages.delivery.number') }} {{ $delivery->delivery_number }}
    <span class="badge bg-secondary">{{ $delivery->status }}</span>
</h4>
<p class="text-muted">
    {{ __('messages.delivery.order') }}: <a href="{{ route('sales-orders.show', $delivery->salesOrder) }}">{{ $delivery->salesOrder->order_number }}</a> —
    {{ $delivery->salesOrder->customer->name_ar }} |
    {{ __('messages.delivery.vehicle') }}: {{ $delivery->vehicle?->plate_number ?? '—' }} |
    {{ __('messages.delivery.driver') }}: {{ $delivery->driver?->name ?? '—' }}
</p>

<div class="card mb-4">
    <table class="table mb-0">
        <thead class="table-light"><tr><th>{{ __('messages.delivery.item') }}</th><th>{{ __('messages.delivery.quantity') }}</th></tr></thead>
        <tbody>
        @foreach($delivery->items as $i)
            <tr><td>{{ $i->salesOrderItem->batch->batch_number }} — {{ $i->salesOrderItem->batch->item->name_ar }}</td><td>{{ $i->quantity }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>

@if(in_array($delivery->status, ['pending', 'loaded', 'in_transit']))
<div class="row">
    <div class="col-md-6">
        <div class="card p-3">
            <h6>{{ __('messages.delivery.confirm') }}</h6>
            <form action="{{ route('delivery-orders.confirm', $delivery) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input name="signed_by_name" class="form-control mb-2" placeholder="{{ __('messages.delivery.signed_by') }}" required>
                <label class="form-label small">{{ __('messages.delivery.proof_photo') }}</label>
                <input type="file" name="proof_photo" accept="image/*" class="form-control mb-2">
                <label class="form-label small">{{ __('messages.delivery.proof_signature') }}</label>
                <input type="file" name="proof_signature" accept="image/*" class="form-control mb-2">
                <button class="btn btn-success w-100">{{ __('messages.delivery.confirm') }}</button>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h6>{{ __('messages.delivery.mark_failed') }}</h6>
            <form action="{{ route('delivery-orders.fail', $delivery) }}" method="POST" class="d-flex gap-2">
                @csrf
                <input name="failure_reason" class="form-control" placeholder="{{ __('messages.delivery.failure_reason') }}" required>
                <button class="btn btn-outline-danger">{{ __('messages.delivery.mark_failed') }}</button>
            </form>
        </div>
    </div>
</div>
@elseif($delivery->status === 'delivered')
    <p class="text-success">✓ {{ __('messages.delivery.confirmed') }} — {{ $delivery->signed_by_name }} ({{ $delivery->delivered_at }})</p>
    <div class="d-flex gap-3">
        @if($delivery->proof_photo_path)
            <div><small class="d-block">{{ __('messages.delivery.proof_photo') }}</small><img src="{{ asset('storage/' . $delivery->proof_photo_path) }}" style="max-width:200px;" class="rounded border"></div>
        @endif
        @if($delivery->proof_signature_path)
            <div><small class="d-block">{{ __('messages.delivery.proof_signature') }}</small><img src="{{ asset('storage/' . $delivery->proof_signature_path) }}" style="max-width:200px;" class="rounded border"></div>
        @endif
    </div>
@elseif($delivery->status === 'failed')
    <p class="text-danger">✗ {{ $delivery->failure_reason }}</p>
@endif
@endsection
