@extends('layouts.app')
@section('title', __('messages.customer.edit'))
@section('content')
<h4 class="mb-3">{{ __('messages.customer.edit') }}: {{ $customer->name_ar }}</h4>
<div class="card p-4" style="max-width:700px;">
<form action="{{ route('customers.update', $customer) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">{{ __('messages.customer.name_ar') }}</label><input name="name_ar" class="form-control" required value="{{ $customer->name_ar }}"></div>
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.customer.type') }}</label>
            <select name="customer_type" class="form-select">
                @foreach(['retail','wholesale','contractor','project','government'] as $t)
                    <option value="{{ $t }}" {{ $customer->customer_type===$t?'selected':'' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6"><label class="form-label">{{ __('messages.customer.credit_limit') }}</label><input name="credit_limit" type="number" step="0.01" class="form-control" value="{{ $customer->credit_limit }}"></div>
        <div class="col-md-6">
            <label class="form-label">{{ __('messages.common.status') }}</label>
            <select name="status" class="form-select">
                @foreach(['active','inactive','blocked'] as $s)
                    <option value="{{ $s }}" {{ $customer->status===$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <button class="btn btn-success mt-4">{{ __('messages.common.update') }}</button>
    <a href="{{ route('customers.index') }}" class="btn btn-light mt-4">{{ __('messages.common.cancel') }}</a>
</form>
</div>
@endsection
