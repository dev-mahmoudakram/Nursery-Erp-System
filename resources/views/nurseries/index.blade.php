@extends('layouts.app')
@section('title', __('messages.nursery.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.nursery.title') }}</h4>
    <a href="{{ route('nurseries.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.nursery.add') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th>{{ __('messages.nursery.code') }}</th>
            <th>{{ __('messages.nursery.name_ar') }}</th>
            <th>{{ __('messages.nursery.city') }}</th>
            <th>{{ __('messages.nursery.locations_count') }}</th>
            <th>{{ __('messages.nursery.batches_count') }}</th>
            <th>{{ __('messages.common.status') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($nurseries as $n)
        <tr>
            <td>{{ $n->code }}</td>
            <td>{{ app()->getLocale() === 'en' && $n->name_en ? $n->name_en : $n->name_ar }}</td>
            <td>{{ $n->city }}</td>
            <td>{{ $n->locations_count }}</td>
            <td>{{ $n->batches_count }}</td>
            <td>
                @if($n->is_active)
                    <span class="badge bg-success">{{ __('messages.common.active') }}</span>
                @else
                    <span class="badge bg-secondary">{{ __('messages.common.inactive') }}</span>
                @endif
            </td>
            <td class="text-end">
                <a href="{{ route('nurseries.edit', $n) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.edit') }}</a>
                <form action="{{ route('nurseries.destroy', $n) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.common.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">{{ __('messages.common.delete') }}</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $nurseries->links() }}</div>
@endsection
