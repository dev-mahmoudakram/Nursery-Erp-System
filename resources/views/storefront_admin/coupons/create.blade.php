@extends('layouts.app')
@section('title', __('messages.nav.coupons'))
@section('content')
<h4 class="mb-3">+ {{ __('messages.nav.coupons') }}</h4>
<div class="card p-4" style="max-width:600px;">
<form action="{{ route('storefront-admin.coupons.store') }}" method="POST">
    @csrf
    <div class="mb-3"><label class="form-label">{{ __('messages.storefront.code') }}</label><input name="code" class="form-control" required></div>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.storefront.discount_type') }}</label>
            <select name="discount_type" class="form-select">
                <option value="percent">percent</option><option value="fixed">fixed</option>
            </select>
        </div>
        <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.discount_value') }}</label><input name="discount_value" type="number" step="0.01" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.valid_from') }}</label><input type="date" name="valid_from" class="form-control"></div>
        <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.valid_until') }}</label><input type="date" name="valid_until" class="form-control"></div>
        <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.usage_limit') }}</label><input type="number" name="usage_limit" class="form-control"></div>
    </div>
    <button class="btn btn-success mt-3">{{ __('messages.common.save') }}</button>
    <a href="{{ route('storefront-admin.coupons.index') }}" class="btn btn-light mt-3">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
