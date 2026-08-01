@extends('layouts.app')
@section('title', __('messages.b2b_contract.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.b2b_contract.add') }}</h4>
<div class="card p-4">
<form action="{{ route('b2b-contracts.store') }}" method="POST">
    @csrf
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.b2b_contract.customer') }}</label>
            <select name="customer_id" class="form-select" required>
                @foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name_ar }} ({{ $c->customer_type }})</option>@endforeach
            </select>
        </div>
        <div class="col-md-3"><label class="form-label">{{ __('messages.b2b_contract.start_date') }}</label><input type="date" name="start_date" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label">{{ __('messages.b2b_contract.end_date') }}</label><input type="date" name="end_date" class="form-control" required></div>
        <div class="col-md-2"><label class="form-label">{{ __('messages.b2b_contract.credit_terms_days') }}</label><input type="number" name="credit_terms_days" class="form-control" value="30" required></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.b2b_contract.credit_limit') }}</label><input type="number" step="0.01" name="contract_credit_limit" class="form-control" placeholder="{{ __('messages.customer.credit_limit') }}"></div>
    </div>

    <h6>{{ __('messages.b2b_contract.special_prices') }}</h6>
    <div id="itemsWrap">
        <div class="row g-2 mb-2 item-row">
            <div class="col-md-8">
                <select name="items[0][item_id]" class="form-select">
                    <option value="">—</option>
                    @foreach($items as $i)<option value="{{ $i->id }}">{{ $i->item_code }} - {{ $i->name_ar }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3"><input type="number" step="0.01" name="items[0][contract_price]" class="form-control" placeholder="{{ __('messages.b2b_contract.contract_price') }}"></div>
            <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100 remove-row">×</button></div>
        </div>
    </div>
    <button type="button" id="addRow" class="btn btn-sm btn-outline-success mb-3">{{ __('messages.b2b_contract.add_item') }}</button>
    <br>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('b2b-contracts.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
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
