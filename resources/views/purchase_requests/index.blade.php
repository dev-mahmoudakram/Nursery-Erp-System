@extends('layouts.app')
@section('title', __('messages.purchase_request.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.purchase_request.title') }}</h4>
    <a href="{{ route('purchase-requests.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.purchase_request.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.purchase_request.number') }}</th><th>{{ __('messages.purchase_request.item') }}</th><th>{{ __('messages.purchase_request.nursery') }}</th><th>{{ __('messages.purchase_request.quantity') }}</th><th>{{ __('messages.purchase_request.status') }}</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($requests as $r)
        <tr>
            <td>{{ $r->request_number }}</td>
            <td>{{ $r->item->name_ar }}</td>
            <td>{{ $r->nursery->name_ar }}</td>
            <td>{{ $r->quantity }}</td>
            <td><span class="badge bg-{{ $r->status==='approved'?'success':($r->status==='rejected'?'danger':'secondary') }}">{{ $r->status }}</span></td>
            <td>
                @if($r->status === 'pending')
                    <form action="{{ route('purchase-requests.approve', $r) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-success">{{ __('messages.purchase_request.approve') }}</button></form>
                    <form action="{{ route('purchase-requests.reject', $r) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-outline-danger">{{ __('messages.purchase_request.reject') }}</button></form>
                @elseif($r->status === 'approved')
                    <a href="{{ route('purchase-orders.create', ['purchase_request_id' => $r->id]) }}" class="btn btn-sm btn-primary">{{ __('messages.purchase_order.add') }}</a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $requests->links() }}</div>
@endsection
