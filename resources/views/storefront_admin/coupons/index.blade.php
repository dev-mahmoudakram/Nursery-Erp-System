@extends('layouts.app')
@section('title', __('messages.nav.coupons'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.nav.coupons') }}</h4>
    <a href="{{ route('storefront-admin.coupons.create') }}" class="btn btn-success btn-sm">+</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>{{ __('messages.storefront.code') }}</th><th>{{ __('messages.storefront.discount_type') }}</th><th>{{ __('messages.storefront.discount_value') }}</th><th>Used</th><th>{{ __('messages.common.status') }}</th></tr></thead>
    <tbody>
    @foreach($coupons as $c)
        <tr>
            <td>{{ $c->code }}</td><td>{{ $c->discount_type }}</td><td>{{ $c->discount_value }}</td>
            <td>{{ $c->used_count }}{{ $c->usage_limit ? '/'.$c->usage_limit : '' }}</td>
            <td><span class="badge bg-{{ $c->is_active?'success':'secondary' }}">{{ $c->is_active?'active':'inactive' }}</span></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $coupons->links() }}</div>
@endsection
