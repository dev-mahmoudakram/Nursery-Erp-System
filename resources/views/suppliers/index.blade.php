@extends('layouts.app')
@section('title', __('messages.supplier.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.supplier.title') }}</h4>
    <a href="{{ route('suppliers.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.supplier.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>{{ __('messages.supplier.code') }}</th><th>{{ __('messages.supplier.name_ar') }}</th><th>{{ __('messages.supplier.city') }}</th><th>{{ __('messages.supplier.rating') }}</th><th>{{ __('messages.common.status') }}</th></tr>
    </thead>
    <tbody>
    @foreach($suppliers as $s)
        <tr>
            <td>{{ $s->supplier_code }}</td>
            <td>{{ app()->getLocale()==='en' && $s->name_en ? $s->name_en : $s->name_ar }}</td>
            <td>{{ $s->city }}</td>
            <td>⭐ {{ $s->rating }}</td>
            <td><span class="badge bg-{{ $s->status==='active'?'success':'secondary' }}">{{ $s->status }}</span></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $suppliers->links() }}</div>
@endsection
