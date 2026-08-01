@extends('layouts.app')
@section('title', __('messages.batch.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.batch.title') }}</h4>
    <a href="{{ route('batches.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.batch.add') }}</a>
</div>
<form class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="nursery_id" class="form-select" onchange="this.form.submit()">
            <option value="">{{ app()->getLocale() === 'ar' ? 'كل المشاتل' : 'All nurseries' }}</option>
            @foreach($nurseries as $n)
                <option value="{{ $n->id }}" {{ request('nursery_id') == $n->id ? 'selected' : '' }}>{{ $n->name_ar }}</option>
            @endforeach
        </select>
    </div>
</form>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th></th>
            <th>{{ __('messages.batch.batch_number') }}</th>
            <th>{{ __('messages.batch.item') }}</th>
            <th>{{ __('messages.batch.nursery') }}</th>
            <th>{{ __('messages.batch.location') }}</th>
            <th>{{ __('messages.batch.quantity') }}</th>
            <th>{{ __('messages.batch.reserved_quantity') }}</th>
            <th>{{ __('messages.batch.available_quantity') }}</th>
            <th>{{ __('messages.common.status') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($batches as $b)
        <tr>
            <td>
                @if($b->image_path)
                    <img src="{{ asset('storage/' . $b->image_path) }}" style="width:36px;height:36px;object-fit:cover;" class="rounded">
                @else
                    <span class="text-muted">🌿</span>
                @endif
            </td>
            <td>{{ $b->batch_number }}</td>
            <td>{{ app()->getLocale() === 'en' && $b->item->name_en ? $b->item->name_en : $b->item->name_ar }}</td>
            <td>{{ $b->nursery->name_ar }}</td>
            <td>{{ $b->location?->code }}</td>
            <td>{{ $b->quantity }}</td>
            <td>{{ $b->reserved_quantity }}</td>
            <td><strong class="text-success">{{ $b->available_quantity }}</strong></td>
            <td><span class="badge bg-secondary">{{ $b->lifecycle_status }}</span></td>
            <td><a href="{{ route('batches.show', $b) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.view') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $batches->links() }}</div>
@endsection
