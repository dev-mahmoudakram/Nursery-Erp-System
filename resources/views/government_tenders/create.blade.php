@extends('layouts.app')
@section('title', __('messages.tender.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.tender.add') }}</h4>
<div class="card p-4">
<form action="{{ route('government-tenders.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">{{ __('messages.tender.title_field') }}</label><input name="title" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">{{ __('messages.tender.entity_name') }}</label><input name="government_entity_name" class="form-control" required></div>
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.tender.customer') }}</label>
            <select name="customer_id" class="form-select">
                <option value="">—</option>
                @foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name_ar }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3"><label class="form-label">{{ __('messages.tender.announcement_date') }}</label><input type="date" name="announcement_date" class="form-control"></div>
        <div class="col-md-3"><label class="form-label">{{ __('messages.tender.submission_deadline') }}</label><input type="date" name="submission_deadline" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.tender.document_fee') }}</label><input type="number" step="0.01" name="tender_document_fee" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.tender.bid_bond') }}</label><input type="number" step="0.01" name="bid_bond_amount" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">{{ __('messages.tender.estimated_value') }}</label><input type="number" step="0.01" name="estimated_value" class="form-control"></div>
    </div>
    <button class="btn btn-success mt-4">{{ __('messages.common.save') }}</button>
    <a href="{{ route('government-tenders.index') }}" class="btn btn-light mt-4">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
