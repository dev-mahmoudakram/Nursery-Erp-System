@extends('layouts.app')
@section('title', __('messages.purchase_order.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.purchase_order.title') }}</h4>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.purchase_order.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.purchase_order.number') }}</th><th>{{ __('messages.purchase_order.supplier') }}</th><th>{{ __('messages.purchase_order.destination_nursery') }}</th><th>{{ __('messages.purchase_order.total') }}</th><th>{{ __('messages.purchase_order.status') }}</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($orders as $o)
        <tr>
            <td>{{ $o->po_number }}</td>
            <td>{{ $o->supplier->name_ar }}</td>
            <td>{{ $o->destinationNursery->name_ar }}</td>
            <td>{{ $o->total }}</td>
            <td><span class="badge bg-{{ $o->status==='received'?'success':'secondary' }}">{{ $o->status }}</span></td>
            <td><a href="{{ route('purchase-orders.show', $o) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.view') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
