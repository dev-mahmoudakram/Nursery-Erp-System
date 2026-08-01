@extends('layouts.app')
@section('title', __('messages.nav.online_orders'))
@section('content')
<h4 class="mb-3">{{ __('messages.nav.online_orders') }}</h4>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>{{ __('messages.storefront.order_number') }}</th><th>{{ __('messages.storefront.customer_name') }}</th><th>{{ __('messages.order.total') }}</th><th>{{ __('messages.order.status') }}</th><th></th></tr></thead>
    <tbody>
    @foreach($orders as $o)
        <tr>
            <td>{{ $o->order_number }}</td>
            <td>{{ $o->customer_name }} ({{ $o->phone }})</td>
            <td>{{ $o->total }}</td>
            <td><span class="badge bg-{{ $o->status==='confirmed'?'success':($o->status==='cancelled'?'danger':'secondary') }}">{{ $o->status }}</span></td>
            <td><a href="{{ route('storefront-admin.orders.show', $o) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.view') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
