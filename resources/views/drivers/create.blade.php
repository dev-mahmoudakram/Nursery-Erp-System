@extends('layouts.app')
@section('title', __('messages.driver.add'))
@section('content')
<h4 class="mb-3">{{ __('messages.driver.add') }}</h4>
<div class="card p-4" style="max-width:500px;">
<form action="{{ route('drivers.store') }}" method="POST">
    @csrf
    <div class="mb-3"><label class="form-label">{{ __('messages.driver.name') }}</label><input name="name" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.driver.phone') }}</label><input name="phone" class="form-control"></div>
    <div class="mb-3"><label class="form-label">{{ __('messages.driver.license_number') }}</label><input name="license_number" class="form-control"></div>
    <button class="btn btn-success">{{ __('messages.common.save') }}</button>
    <a href="{{ route('drivers.index') }}" class="btn btn-light">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
