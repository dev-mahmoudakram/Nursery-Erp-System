@extends('layouts.app')
@section('title', __('messages.b2b_contract.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.b2b_contract.title') }}</h4>
    <a href="{{ route('b2b-contracts.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.b2b_contract.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.b2b_contract.number') }}</th><th>{{ __('messages.b2b_contract.customer') }}</th><th>{{ __('messages.b2b_contract.start_date') }}</th><th>{{ __('messages.b2b_contract.end_date') }}</th><th>{{ __('messages.b2b_contract.status') }}</th></tr>
    </thead>
    <tbody>
    @foreach($contracts as $c)
        <tr>
            <td>{{ $c->contract_number }}</td>
            <td>{{ $c->customer->name_ar }}</td>
            <td>{{ $c->start_date->toDateString() }}</td>
            <td>{{ $c->end_date->toDateString() }}</td>
            <td><span class="badge bg-{{ $c->status==='active'?'success':'secondary' }}">{{ $c->status }}</span></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $contracts->links() }}</div>
@endsection
