@extends('layouts.app')
@section('title', __('messages.opportunity.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.opportunity.add') }}</h4>
<div class="card p-4" style="max-width:700px;">
<form action="{{ route('opportunities.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label class="form-label">{{ __('messages.opportunity.customer') }}</label>
        <select name="customer_id" class="form-select" required>
            @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name_ar }} ({{ $c->customer_code }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3"><label class="form-label">{{ __('messages.opportunity.sales_title') }}</label><input name="title" class="form-control" required></div>
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">{{ __('messages.opportunity.expected_value') }}</label><input name="expected_value" type="number" step="0.01" class="form-control"></div>
        <div class="col-md-6"><label class="form-label">{{ __('messages.opportunity.expected_close_date') }}</label><input name="expected_close_date" type="date" class="form-control"></div>
    </div>
    <button class="btn btn-success mt-4">{{ __('messages.common.save') }}</button>
    <a href="{{ route('opportunities.index') }}" class="btn btn-light mt-4">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
