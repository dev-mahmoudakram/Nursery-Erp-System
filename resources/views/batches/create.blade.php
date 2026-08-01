@extends('layouts.app')
@section('title', __('messages.batch.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.batch.add') }}</h4>
<div class="card p-4" style="max-width:700px;">
<form action="{{ route('batches.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.batch.batch_number') }}</label>
            <input name="batch_number" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.batch.item') }}</label>
            <select name="item_id" class="form-select" required>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->item_code }} - {{ $item->name_ar }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.batch.nursery') }}</label>
            <select name="nursery_id" class="form-select" required>
                @foreach($nurseries as $n)
                    <option value="{{ $n->id }}">{{ $n->name_ar }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.batch.location') }}</label>
            <select name="location_id" class="form-select">
                <option value="">—</option>
                @foreach($locations as $l)
                    <option value="{{ $l->id }}">{{ $l->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.batch.production_date') }}</label>
            <input type="date" name="production_date" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.batch.quantity') }}</label>
            <input type="number" step="0.01" name="quantity" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.batch.size') }}</label>
            <input name="size" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.item.quality_grade') }}</label>
            <select name="quality_grade" class="form-select">
                <option value="A">A</option><option value="B">B</option><option value="C">C</option>
            </select>
        </div>
    </div>
    <div class="mb-3 mt-3"><label class="form-label">{{ __('messages.batch.photo') }}</label><input type="file" name="image" accept="image/*" class="form-control"></div>
    <button class="btn btn-success mt-4">{{ __('messages.common.save') }}</button>
    <a href="{{ route('batches.index') }}" class="btn btn-light mt-4">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif
@endsection
