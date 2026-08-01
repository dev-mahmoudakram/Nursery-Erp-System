@extends('layouts.app')
@section('title', __('messages.tender.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.tender.title') }}</h4>
    <a href="{{ route('government-tenders.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.tender.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.tender.number') }}</th><th>{{ __('messages.tender.title_field') }}</th><th>{{ __('messages.tender.entity_name') }}</th><th>{{ __('messages.tender.submission_deadline') }}</th><th>{{ __('messages.tender.status') }}</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($tenders as $t)
        <tr>
            <td>{{ $t->tender_number }}</td>
            <td>{{ $t->title }}</td>
            <td>{{ $t->government_entity_name }}</td>
            <td>{{ $t->submission_deadline }}</td>
            <td><span class="badge bg-{{ $t->status==='won'?'success':($t->status==='lost'?'danger':'secondary') }}">{{ $t->status }}</span></td>
            <td><a href="{{ route('government-tenders.show', $t) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.view') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $tenders->links() }}</div>
@endsection
