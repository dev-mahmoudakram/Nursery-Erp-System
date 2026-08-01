@extends('layouts.app')
@section('title', __('messages.nursery.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.nursery.add') }}</h4>
<div class="card p-4" style="max-width:600px;">
<form action="{{ route('nurseries.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.code') }}</label>
        <input name="code" class="form-control" required value="{{ old('code') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.name_ar') }}</label>
        <input name="name_ar" class="form-control" required value="{{ old('name_ar') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.name_en') }}</label>
        <input name="name_en" class="form-control" value="{{ old('name_en') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.city') }}</label>
        <input name="city" class="form-control" value="{{ old('city') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.address') }}</label>
        <input name="address" class="form-control" value="{{ old('address') }}">
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
        <label class="form-check-label">{{ __('messages.common.active') }}</label>
    </div>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('nurseries.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif
@endsection
