@extends('layouts.app')
@section('title', __('messages.purchase_request.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.purchase_request.add') }}</h4>
<div class="card p-4" style="max-width:600px;">
<form action="{{ route('purchase-requests.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">{{ __('messages.purchase_request.item') }}</label>
        <select name="item_id" class="form-select" required>
            @foreach($items as $i)<option value="{{ $i->id }}">{{ $i->item_code }} - {{ $i->name_ar }}</option>@endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('messages.purchase_request.nursery') }}</label>
        <select name="nursery_id" class="form-select" required>
            @foreach($nurseries as $n)<option value="{{ $n->id }}">{{ $n->name_ar }}</option>@endforeach
        </select>
    </div>
    <div class="mb-3"><label class="form-label">{{ __('messages.purchase_request.quantity') }}</label><input type="number" step="0.01" name="quantity" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.batch.notes_optional') }}</label><textarea name="notes" class="form-control"></textarea></div>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('purchase-requests.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
