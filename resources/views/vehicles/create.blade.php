@extends('layouts.app')
@section('title', __('messages.vehicle.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.vehicle.add') }}</h4>
<div class="card p-4" style="max-width:500px;">
<form action="{{ route('vehicles.store') }}" method="POST">
    @csrf
    <div class="mb-3"><label class="form-label">{{ __('messages.vehicle.plate_number') }}</label><input name="plate_number" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.vehicle.type') }}</label><input name="type" class="form-control"></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.vehicle.capacity') }}</label><input name="capacity" type="number" step="0.01" class="form-control"></div>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('vehicles.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
