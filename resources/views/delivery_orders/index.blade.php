@extends('layouts.app')
@section('title', __('messages.delivery.title'))
@section('content')
<h4 class="mb-3">{{ __('messages.delivery.title') }}</h4>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.delivery.number') }}</th><th>{{ __('messages.delivery.order') }}</th><th>{{ __('messages.delivery.vehicle') }}</th><th>{{ __('messages.delivery.driver') }}</th><th>{{ __('messages.delivery.status') }}</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($deliveries as $d)
        <tr>
            <td>{{ $d->delivery_number }}</td>
            <td>{{ $d->salesOrder->order_number }} ({{ $d->salesOrder->customer->name_ar }})</td>
            <td>{{ $d->vehicle?->plate_number }}</td>
            <td>{{ $d->driver?->name }}</td>
            <td><span class="badge bg-{{ $d->status==='delivered'?'success':($d->status==='failed'?'danger':'secondary') }}">{{ $d->status }}</span></td>
            <td><a href="{{ route('delivery-orders.show', $d) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.view') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $deliveries->links() }}</div>
@endsection
