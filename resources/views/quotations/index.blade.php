@extends('layouts.app')
@section('title', __('messages.quotation.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.quotation.title') }}</h4>
    <a href="{{ route('quotations.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.quotation.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.quotation.number') }}</th><th>{{ __('messages.quotation.customer') }}</th><th>{{ __('messages.quotation.valid_until') }}</th><th>{{ __('messages.quotation.total') }}</th><th>{{ __('messages.quotation.margin') }}</th><th>{{ __('messages.quotation.status') }}</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($quotations as $q)
        <tr>
            <td>{{ $q->quotation_number }}</td>
            <td>{{ $q->customer->name_ar }}</td>
            <td>{{ $q->valid_until }}</td>
            <td>{{ $q->total }}</td>
            <td>{{ $q->margin_percent }}</td>
            <td><span class="badge bg-secondary">{{ $q->status }}</span></td>
            <td><a href="{{ route('quotations.show', $q) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.view') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $quotations->links() }}</div>
@endsection
