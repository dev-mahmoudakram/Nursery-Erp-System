@extends('layouts.app')
@section('title', __('messages.vehicle.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.vehicle.title') }}</h4>
    <a href="{{ route('vehicles.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.vehicle.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>{{ __('messages.vehicle.plate_number') }}</th><th>{{ __('messages.vehicle.type') }}</th><th>{{ __('messages.vehicle.capacity') }}</th><th>{{ __('messages.common.status') }}</th></tr></thead>
    <tbody>
    @foreach($vehicles as $v)
        <tr><td>{{ $v->plate_number }}</td><td>{{ $v->type }}</td><td>{{ $v->capacity }}</td><td><span class="badge bg-{{ $v->status==='available'?'success':'secondary' }}">{{ $v->status }}</span></td></tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $vehicles->links() }}</div>
@endsection
