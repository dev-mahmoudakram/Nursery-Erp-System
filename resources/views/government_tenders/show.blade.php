@extends('layouts.app')
@section('title', $tender->tender_number)
@section('content')
<h4 class="mb-3">{{ $tender->tender_number }} — {{ $tender->title }}
    <span class="badge bg-{{ $tender->status==='won'?'success':($tender->status==='lost'?'danger':'secondary') }}">{{ $tender->status }}</span>
</h4>
<p class="text-muted">
    {{ __('messages.tender.entity_name') }}: {{ $tender->government_entity_name }} |
    {{ __('messages.tender.submission_deadline') }}: {{ $tender->submission_deadline }} |
    {{ __('messages.tender.estimated_value') }}: {{ $tender->estimated_value }}
</p>

@php $latest = $tender->evaluations->last(); @endphp

<div class="card p-4 mb-4">
    <h6>{{ __('messages.tender.evaluate') }}</h6>
    <form action="{{ route('government-tenders.evaluate', $tender) }}" method="POST" class="row g-2">
        @csrf
        <div class="col-md-3"><input type="number" name="expected_margin_percent" class="form-control" placeholder="{{ __('messages.tender.expected_margin') }}" min="0" max="100" required></div>
        <div class="col-md-3">
            <select name="risk_level" class="form-select">
                <option value="low">low</option><option value="medium">medium</option><option value="high">high</option>
            </select>
        </div>
        <div class="col-md-2"><input type="number" name="operational_capacity_score" class="form-control" placeholder="{{ __('messages.tender.operational_capacity') }}" min="1" max="5" required></div>
        <div class="col-md-2"><input type="number" name="activity_fit_score" class="form-control" placeholder="{{ __('messages.tender.activity_fit') }}" min="1" max="5" required></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">{{ __('messages.tender.evaluate') }}</button></div>
    </form>
</div>

@if($latest)
<div class="card p-4 mb-4">
    <p>{{ __('messages.tender.system_recommendation') }}:
        <span class="badge bg-{{ $latest->system_recommendation==='bid'?'success':'danger' }}">{{ $latest->system_recommendation }}</span>
    </p>

    @if(!$latest->final_decision)
        <h6>{{ __('messages.tender.decide') }}</h6>
        <form action="{{ route('government-tenders.decide', $tender) }}" method="POST" class="d-flex gap-2">
            @csrf
            <select name="final_decision" class="form-select" style="max-width:200px">
                <option value="bid">bid</option>
                <option value="no_bid">no_bid</option>
            </select>
            <button class="btn btn-primary">{{ __('messages.tender.decide') }}</button>
        </form>
    @else
        <p>{{ __('messages.tender.final_decision') }}: <span class="badge bg-secondary">{{ $latest->final_decision }}</span></p>
    @endif
</div>
@endif

@if($tender->status === 'preparing_offer')
    <form action="{{ route('government-tenders.submitted', $tender) }}" method="POST" class="mb-4">
        @csrf
        <button class="btn btn-success">{{ __('messages.tender.mark_submitted') }}</button>
    </form>
@endif

@if($tender->status === 'submitted')
<div class="card p-4">
    <h6>{{ __('messages.tender.record_outcome') }}</h6>
    <form action="{{ route('government-tenders.outcome', $tender) }}" method="POST" class="row g-2">
        @csrf
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="won">won</option>
                <option value="lost">lost</option>
            </select>
        </div>
        <div class="col-md-6"><input name="outcome_reason" class="form-control" placeholder="{{ __('messages.tender.outcome_reason') }}" required></div>
        <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('messages.tender.record_outcome') }}</button></div>
    </form>
</div>
@elseif(in_array($tender->status, ['won', 'lost']))
    <p class="mt-3"><strong>{{ __('messages.tender.outcome_reason') }}:</strong> {{ $tender->outcome_reason }}</p>
@endif

<div class="card p-4 mt-4">
    <h6>{{ __('messages.tender.documents') }}</h6>
    <form action="{{ route('government-tenders.documents.store', $tender) }}" method="POST" enctype="multipart/form-data" class="row g-2 mb-3">
        @csrf
        <div class="col-md-4"><input name="name" class="form-control" placeholder="{{ __('messages.tender.document_name') }}" required></div>
        <div class="col-md-5"><input type="file" name="file" class="form-control" required></div>
        <div class="col-md-3"><button class="btn btn-outline-primary w-100">{{ __('messages.tender.upload') }}</button></div>
    </form>
    <ul class="list-group">
        @forelse($tender->documents as $doc)
            <li class="list-group-item d-flex justify-content-between">
                {{ $doc->name }}
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">{{ __('messages.common.view') }}</a>
            </li>
        @empty
            <li class="list-group-item text-muted text-center">—</li>
        @endforelse
    </ul>
</div>
@endsection
