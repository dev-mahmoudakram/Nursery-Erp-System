@extends('layouts.app')
@section('title', __('messages.item.title'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.item.title') }}</h4>
    <a href="{{ route('items.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.item.add') }}</a>
</div>
<form class="mb-3" method="GET">
    <input class="form-control" name="q" placeholder="{{ __('messages.common.search_placeholder') }}" value="{{ request('q') }}">
</form>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th></th>
            <th>{{ __('messages.item.item_code') }}</th>
            <th>{{ __('messages.item.name_ar') }}</th>
            <th>{{ __('messages.item.category') }}</th>
            <th>{{ __('messages.item.quality_grade') }}</th>
            <th>{{ __('messages.item.retail_price') }}</th>
            <th>{{ __('messages.item.wholesale_price') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" style="width:40px;height:40px;object-fit:cover;" class="rounded">
                @else
                    <span class="text-muted">🌿</span>
                @endif
            </td>
            <td>{{ $item->item_code }}</td>
            <td>{{ app()->getLocale() === 'en' && $item->name_en ? $item->name_en : $item->name_ar }}</td>
            <td>{{ $item->mainCategory?->name_ar }}</td>
            <td><span class="badge bg-info text-dark">{{ $item->quality_grade }}</span></td>
            <td>{{ $item->retail_price }}</td>
            <td>{{ $item->wholesale_price }}</td>
            <td class="text-end">
                <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.common.edit') }}</a>
                <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.common.confirm_delete') }}')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">{{ __('messages.common.delete') }}</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $items->links() }}</div>
@endsection
