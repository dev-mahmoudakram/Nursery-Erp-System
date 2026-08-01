@extends('layouts.app')
@section('title', __('messages.item.edit'))
@section('content')
<h4 class="mb-3">{{ __('messages.item.edit') }}: {{ $item->name_ar }}</h4>
<div class="card p-4">
<form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    @if($item->image_path)
        <img src="{{ asset('storage/' . $item->image_path) }}" style="max-width:150px;" class="rounded border mb-3 d-block">
    @endif
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.item.item_code') }}</label>
            <input name="item_code" class="form-control" required value="{{ $item->item_code }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.item.name_ar') }}</label>
            <input name="name_ar" class="form-control" required value="{{ $item->name_ar }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('messages.item.quality_grade') }}</label>
            <select name="quality_grade" class="form-select">
                @foreach(['A','B','C'] as $g)
                    <option value="{{ $g }}" {{ $item->quality_grade == $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('messages.item.safety_stock') }}</label>
            <input name="safety_stock" type="number" step="0.01" class="form-control" value="{{ $item->safety_stock }}">
        </div>
    </div>
    <hr class="my-4">
    <h6>{{ __('messages.item.price_lists') }}</h6>
    <div class="row g-3">
        <div class="col-md-2"><label class="form-label">{{ __('messages.item.retail_price') }}</label><input name="retail_price" type="number" step="0.01" class="form-control" value="{{ $item->retail_price }}"></div>
        <div class="col-md-2"><label class="form-label">{{ __('messages.item.wholesale_price') }}</label><input name="wholesale_price" type="number" step="0.01" class="form-control" value="{{ $item->wholesale_price }}"></div>
        <div class="col-md-2"><label class="form-label">{{ __('messages.item.contractor_price') }}</label><input name="contractor_price" type="number" step="0.01" class="form-control" value="{{ $item->contractor_price }}"></div>
        <div class="col-md-2"><label class="form-label">{{ __('messages.item.project_price') }}</label><input name="project_price" type="number" step="0.01" class="form-control" value="{{ $item->project_price }}"></div>
        <div class="col-md-2"><label class="form-label">{{ __('messages.item.government_price') }}</label><input name="government_price" type="number" step="0.01" class="form-control" value="{{ $item->government_price }}"></div>
        <div class="col-md-2"><label class="form-label">{{ __('messages.item.clearance_price') }}</label><input name="clearance_price" type="number" step="0.01" class="form-control" value="{{ $item->clearance_price }}"></div>
    </div>
    <hr class="my-4">
    <div class="mb-3"><label class="form-label">{{ __('messages.item.image') }}</label><input type="file" name="image" accept="image/*" class="form-control"></div>

    <button class="btn btn-success mt-4">{{ __('messages.common.update') }}</button>
    <a href="{{ route('items.index') }}" class="btn btn-light mt-4">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
