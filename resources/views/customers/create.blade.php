@extends('layouts.app')
@section('title', __('messages.customer.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.customer.add') }}</h4>
<div class="card p-4" style="max-width:700px;">
<form action="{{ route('customers.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">{{ __('messages.customer.code') }}</label><input name="customer_code" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.customer.name_ar') }}</label><input name="name_ar" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.customer.name_en') }}</label><input name="name_en" class="form-control"></div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.customer.type') }}</label>
            <select name="customer_type" class="form-select">
                <option value="retail">retail</option>
                <option value="wholesale">wholesale</option>
                <option value="contractor">contractor</option>
                <option value="project">project</option>
                <option value="government">government</option>
            </select>
        </div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.customer.phone') }}</label><input name="phone" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.customer.email') }}</label><input name="email" type="email" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.customer.city') }}</label><input name="city" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.customer.credit_limit') }}</label><input name="credit_limit" type="number" step="0.01" class="form-control" value="0"></div>
    </div>
    <button class="btn btn-success mt-4">{{ __('messages.common.save') }}</button>
    <a href="{{ route('customers.index') }}" class="btn btn-light mt-4">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@if ($errors->any())
    <div class="alert alert-danger mt-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
@endsection
