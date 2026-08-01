@extends('layouts.app')
@section('title', __('messages.nav.reviews'))
@section('content')
<h4 class="mb-3">{{ __('messages.nav.reviews') }}</h4>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>{{ __('messages.item.title') }}</th><th>{{ __('messages.storefront.your_name') }}</th><th>{{ __('messages.storefront.rating') }}</th><th>{{ __('messages.storefront.comment') }}</th><th></th></tr></thead>
    <tbody>
    @forelse($reviews as $r)
        <tr>
            <td>{{ $r->item->name_ar }}</td>
            <td>{{ $r->customer_name }}</td>
            <td>{{ str_repeat('⭐', $r->rating) }}</td>
            <td>{{ $r->comment }}</td>
            <td>
                <form action="{{ route('storefront-admin.reviews.approve', $r) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-success">✓</button></form>
                <form action="{{ route('storefront-admin.reviews.reject', $r) }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-outline-danger">✗</button></form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center text-muted">—</td></tr>
    @endforelse
    </tbody>
</table>
</div>
<div class="mt-3">{{ $reviews->links() }}</div>
@endsection
