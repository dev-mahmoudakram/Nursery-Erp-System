@extends('layouts.app')
@section('title', __('messages.customer.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.customer.title') }}</h4>
    <a href="{{ route('customers.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.customer.add') }}</a>
</div>
<form class="mb-3" method="GET">
    <input class="form-control" name="q" placeholder="{{ __('messages.common.search_placeholder') }}" value="{{ request('q') }}">
</form>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th>{{ __('messages.customer.code') }}</th>
            <th>{{ __('messages.customer.name_ar') }}</th>
            <th>{{ __('messages.customer.type') }}</th>
            <th>{{ __('messages.customer.credit_limit') }}</th>
            <th>{{ __('messages.customer.current_balance') }}</th>
            <th>{{ __('messages.common.status') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($customers as $c)
        <tr>
            <td>{{ $c->customer_code }}</td>
            <td><a href="{{ route('customers.show', $c) }}">{{ app()->getLocale()==='en' && $c->name_en ? $c->name_en : $c->name_ar }}</a></td>
            <td><span class="badge bg-info text-dark">{{ $c->customer_type }}</span></td>
            <td>{{ $c->credit_limit }}</td>
            <td>{{ $c->current_balance }}</td>
            <td><span class="badge bg-{{ $c->status === 'active' ? 'success' : 'secondary' }}">{{ $c->status }}</span></td>
            <td class="text-end"><a href="{{ route('customers.edit', $c) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.edit') }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $customers->links() }}</div>
@endsection
