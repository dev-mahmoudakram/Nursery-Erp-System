@extends('layouts.app')
@section('title', __('messages.driver.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.driver.title') }}</h4>
    <a href="{{ route('drivers.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.driver.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>{{ __('messages.driver.name') }}</th><th>{{ __('messages.driver.phone') }}</th><th>{{ __('messages.driver.license_number') }}</th><th>{{ __('messages.common.status') }}</th></tr></thead>
    <tbody>
    @foreach($drivers as $d)
        <tr><td>{{ $d->name }}</td><td>{{ $d->phone }}</td><td>{{ $d->license_number }}</td><td><span class="badge bg-{{ $d->status==='available'?'success':'secondary' }}">{{ $d->status }}</span></td></tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $drivers->links() }}</div>
@endsection
