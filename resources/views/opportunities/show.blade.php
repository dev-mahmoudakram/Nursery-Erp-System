@extends('layouts.app')
@section('title', $opportunity->title)
@section('content')
<h4 class="mb-3">{{ $opportunity->title }} — {{ $opportunity->customer->name_ar }}</h4>

<div class="card p-4 mb-4">
    <h6>{{ __('messages.opportunity.update_stage') }} ({{ __('messages.opportunity.stage') }}: <span class="badge bg-secondary">{{ $opportunity->stage }}</span>)</h6>
    <form action="{{ route('opportunities.stage', $opportunity) }}" method="POST" class="row g-2 mt-1">
        @csrf
        <div class="col-md-3">
            <select name="stage" class="form-select" id="stageSelect" onchange="document.getElementById('lostReasonWrap').style.display = this.value==='lost' ? 'block':'none'">
                @foreach(['target_customer','first_contact','needs_analysis','quotation_sent','negotiation','won','lost','postponed'] as $s)
                    <option value="{{ $s }}" {{ $opportunity->stage===$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6" id="lostReasonWrap" style="display:{{ $opportunity->stage==='lost' ? 'block':'none' }}">
            <input name="lost_reason" class="form-control" placeholder="{{ __('messages.opportunity.lost_reason') }}" value="{{ $opportunity->lost_reason }}">
        </div>
        <div class="col-md-3"><button class="btn btn-primary w-100">{{ __('messages.common.update') }}</button></div>
    </form>
</div>

<div class="card">
    <div class="card-header">{{ __('messages.quotation.title') }}</div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>{{ __('messages.quotation.number') }}</th><th>{{ __('messages.quotation.status') }}</th><th>{{ __('messages.quotation.total') }}</th></tr></thead>
        <tbody>
        @forelse($opportunity->quotations as $q)
            <tr><td><a href="{{ route('quotations.show', $q) }}">{{ $q->quotation_number }}</a></td><td>{{ $q->status }}</td><td>{{ $q->total }}</td></tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted">—</td></tr>
        @endforelse
        </tbody>
    </table>
    <div class="p-3">
        <a href="{{ route('quotations.create', ['opportunity_id' => $opportunity->id]) }}" class="btn btn-sm btn-success">+ {{ __('messages.quotation.add') }}</a>
    </div>
</div>
@endsection
