@extends('layouts.app')
@section('title', __('messages.storefront.publish_new'))
@section('content')
<h4 class="mb-3">{{ __('messages.storefront.publish_new') }}</h4>
<div class="card p-4" style="max-width:600px;">
<form action="{{ route('storefront-admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label class="form-label">{{ __('messages.item.title') }}</label>
        <select name="item_id" class="form-select" required>
            @foreach($items as $i)<option value="{{ $i->id }}">{{ $i->item_code }} - {{ $i->name_ar }}</option>@endforeach
        </select>
    </div>
    <div class="mb-3"><label class="form-label">Display Name (AR)</label><input name="display_name_ar" class="form-control"></div>
    <div class="mb-3"><label class="form-label">Description (AR)</label><textarea name="description_ar" class="form-control"></textarea></div>
    <div class="mb-3"><label class="form-label">Cover Image</label><input type="file" name="cover_image" accept="image/*" class="form-control"></div>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('storefront-admin.products.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
