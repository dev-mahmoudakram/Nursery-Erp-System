@extends('layouts.app')
@section('title', __('messages.quotation.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.quotation.add') }}</h4>
<div class="card p-4">
<form action="{{ route('quotations.store') }}" method="POST">
    @csrf
    @if($opportunityId)
        <input type="hidden" name="sales_opportunity_id" value="{{ $opportunityId }}">
    @endif
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.quotation.customer') }}</label>
            <select name="customer_id" class="form-select" required>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name_ar }} ({{ $c->customer_type }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.quotation.valid_until') }}</label>
            <input type="date" name="valid_until" class="form-control" required value="{{ now()->addDays(14)->toDateString() }}">
        </div>
    </div>

    <h6>{{ __('messages.quotation.batch') }} / {{ __('messages.quotation.quantity') }}</h6>
    <div id="itemsWrap">
        <div class="row g-2 mb-2 item-row">
            <div class="col-md-8">
                <select name="items[0][batch_id]" class="form-select" required>
                    @foreach($batches as $b)
                        <option value="{{ $b->id }}">
                            {{ $b->batch_number }} — {{ $b->item->name_ar }} ({{ __('messages.batch.available_quantity') }}: {{ $b->available_quantity }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="items[0][quantity]" class="form-control" placeholder="{{ __('messages.quotation.quantity') }}" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger w-100 remove-row">×</button>
            </div>
        </div>
    </div>
    <button type="button" id="addRow" class="btn btn-sm btn-outline-success mb-3">{{ __('messages.quotation.add_item') }}</button>
    <br>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('quotations.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@if ($errors->any())
    <div class="alert alert-danger mt-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<script>
let rowIndex = 1;
document.getElementById('addRow').addEventListener('click', function () {
    const wrap = document.getElementById('itemsWrap');
    const row = wrap.querySelector('.item-row').cloneNode(true);
    row.querySelectorAll('select, input').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
        el.value = '';
    });
    wrap.appendChild(row);
    rowIndex++;
});
document.getElementById('itemsWrap').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) e.target.closest('.item-row').remove();
    }
});
</script>
@endsection
