@extends('layouts.app')
@section('title', __('messages.batch.title'))
@section('content')
<h4 class="mb-3">{{ __('messages.batch.batch_number') }} {{ $batch->batch_number }} — {{ $batch->item->name_ar }}</h4>

<div class="row mb-4">
    <div class="col-md-3">
        @if($batch->image_path)
            <img src="{{ asset('storage/' . $batch->image_path) }}" class="img-fluid rounded border">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height:120px;font-size:2.5rem;">🌿</div>
        @endif
        <form action="{{ route('batches.photo', $batch) }}" method="POST" enctype="multipart/form-data" class="mt-2">
            @csrf
            <input type="file" name="image" accept="image/*" class="form-control form-control-sm mb-1" required>
            <button class="btn btn-sm btn-outline-primary w-100">{{ __('messages.batch.update_photo') }}</button>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.batch.quantity') }}</small><h4>{{ $batch->quantity }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ __('messages.batch.reserved_quantity') }}</small><h4>{{ $batch->reserved_quantity }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><small>{{ app()->getLocale()==='ar' ? 'التالف/المعزول' : 'Damaged/Isolated' }}</small><h4>{{ $batch->damaged_quantity + $batch->isolated_quantity }}</h4></div></div>
    <div class="col-md-3"><div class="card p-3 text-center bg-success text-white"><small>{{ __('messages.batch.available_quantity') }}</small><h4>{{ $batch->available_quantity }}</h4></div></div>
</div>

<div class="card p-4 mb-4">
    <h6>{{ __('messages.batch.change_status') }} ({{ app()->getLocale()==='ar' ? 'الحالة الحالية' : 'Current status' }}: <span class="badge bg-secondary">{{ $batch->lifecycle_status }}</span>)</h6>
    <form action="{{ route('batches.status', $batch) }}" method="POST" class="row g-2 mt-1">
        @csrf
        <div class="col-md-4">
            <select name="to_status" class="form-select">
                @foreach(['new_production','growing','under_inspection','ready_for_sale','reserved','preparing','loaded','delivered','returned','needs_rehab','isolated','damaged','disposed'] as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <input name="notes" class="form-control" placeholder="{{ __('messages.batch.notes_optional') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">{{ __('messages.common.update') }}</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">{{ __('messages.batch.movements_log') }}</div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>Type</th><th>{{ __('messages.batch.quantity') }}</th><th>From</th><th>To</th><th>User</th><th>Date</th></tr></thead>
        <tbody>
        @forelse($batch->movements as $m)
            <tr>
                <td>{{ $m->movement_type }}</td>
                <td>{{ $m->quantity }}</td>
                <td>{{ $m->fromLocation?->code }}</td>
                <td>{{ $m->toLocation?->code }}</td>
                <td>{{ $m->user?->name }}</td>
                <td>{{ $m->movement_date }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted">{{ app()->getLocale()==='ar' ? 'لا توجد حركات بعد' : 'No movements yet' }}</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
