@extends('layouts.app')
@section('title', __('messages.opportunity.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.opportunity.title') }}</h4>
    <a href="{{ route('opportunities.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.opportunity.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.opportunity.sales_title') }}</th><th>{{ __('messages.opportunity.customer') }}</th><th>{{ __('messages.opportunity.expected_value') }}</th><th>{{ __('messages.opportunity.stage') }}</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($opportunities as $o)
        <tr>
            <td>{{ $o->title }}</td>
            <td>{{ $o->customer->name_ar }}</td>
            <td>{{ $o->expected_value }}</td>
            <td><span class="badge bg-{{ $o->stage==='won'?'success':($o->stage==='lost'?'danger':'secondary') }}">{{ $o->stage }}</span></td>
            <td><a href="{{ route('opportunities.show', $o) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.view') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $opportunities->links() }}</div>
@endsection
