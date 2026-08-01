@extends('layouts.app')
@section('title', __('messages.nursery.edit'))
@section('content')
<h4 class="mb-3">{{ __('messages.nursery.edit') }}: {{ $nursery->name_ar }}</h4>
<div class="card p-4" style="max-width:600px;">
<form action="{{ route('nurseries.update', $nursery) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.code') }}</label>
        <input name="code" class="form-control" required value="{{ old('code', $nursery->code) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.name_ar') }}</label>
        <input name="name_ar" class="form-control" required value="{{ old('name_ar', $nursery->name_ar) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.name_en') }}</label>
        <input name="name_en" class="form-control" value="{{ old('name_en', $nursery->name_en) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.city') }}</label>
        <input name="city" class="form-control" value="{{ old('city', $nursery->city) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.nursery.address') }}</label>
        <input name="address" class="form-control" value="{{ old('address', $nursery->address) }}">
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $nursery->is_active ? 'checked' : '' }}>
        <label class="form-check-label">{{ __('messages.common.active') }}</label>
    </div>
    <button class="btn btn-success">{{ __('messages.common.update') }}</button>
    <a href="{{ route('nurseries.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
