@extends('layouts.app')
@section('title', __('messages.purchase_order.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.purchase_order.add') }}</h4>
<div class="card p-4">
<form action="{{ route('purchase-orders.store') }}" method="POST">
    @csrf
    @if($purchaseRequestId)
        <input type="hidden" name="purchase_request_id" value="{{ $purchaseRequestId }}">
    @endif
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.purchase_order.supplier') }}</label>
            <select name="supplier_id" class="form-select" required>
                @foreach($suppliers as $s)<option value="{{ $s->id }}">{{ $s->name_ar }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.purchase_order.destination_nursery') }}</label>
            <select name="destination_nursery_id" class="form-select" required>
                @foreach($nurseries as $n)<option value="{{ $n->id }}">{{ $n->name_ar }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">{{ __('messages.purchase_order.expected_date') }}</label>
            <input type="date" name="expected_date" class="form-control">
        </div>
    </div>

    <h6>{{ __('messages.purchase_order.item') }} / {{ __('messages.purchase_order.quantity') }} / {{ __('messages.purchase_order.unit_cost') }}</h6>
    <div id="itemsWrap">
        <div class="row g-2 mb-2 item-row">
            <div class="col-md-6">
                <select name="items[0][item_id]" class="form-select" required>
                    @foreach($items as $i)<option value="{{ $i->id }}">{{ $i->item_code }} - {{ $i->name_ar }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3"><input type="number" step="0.01" name="items[0][quantity]" class="form-control" placeholder="{{ __('messages.purchase_order.quantity') }}" required></div>
            <div class="col-md-2"><input type="number" step="0.01" name="items[0][unit_cost]" class="form-control" placeholder="{{ __('messages.purchase_order.unit_cost') }}" required></div>
            <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100 remove-row">×</button></div>
        </div>
    </div>
    <button type="button" id="addRow" class="btn btn-sm btn-outline-success mb-3">{{ __('messages.purchase_order.add_item') }}</button>
    <br>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('purchase-orders.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
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
