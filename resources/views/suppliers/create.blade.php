@extends('layouts.app')
@section('title', __('messages.supplier.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.supplier.add') }}</h4>
<div class="card p-4" style="max-width:600px;">
<form action="{{ route('suppliers.store') }}" method="POST">
    @csrf
    <div class="mb-3"><label class="form-label">{{ __('messages.supplier.code') }}</label><input name="supplier_code" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.supplier.name_ar') }}</label><input name="name_ar" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.supplier.name_en') }}</label><input name="name_en" class="form-control"></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.supplier.phone') }}</label><input name="phone" class="form-control"></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.supplier.email') }}</label><input name="email" type="email" class="form-control"></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.supplier.city') }}</label><input name="city" class="form-control"></div>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('suppliers.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
