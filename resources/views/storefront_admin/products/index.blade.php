@extends('layouts.app')
@section('title', __('messages.nav.storefront_products'))
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>{{ __('messages.nav.storefront_products') }} <span class="badge bg-secondary">{{ $unpublishedItemsCount }} {{ __('messages.storefront.unpublished_count') }}</span></h4>
    <a href="{{ route('storefront-admin.products.create') }}" class="btn btn-success btn-sm">+ {{ __('messages.storefront.publish_new') }}</a>
</div>
<div class="card">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>{{ __('messages.item.name_ar') }}</th><th>{{ __('messages.storefront.live_stock') }}</th><th>{{ __('messages.common.status') }}</th><th></th></tr></thead>
    <tbody>
    @foreach($products as $p)
        <tr>
            <td>{{ $p->item->name_ar }}</td>
            <td>{{ $p->live_stock }}</td>
            <td><span class="badge bg-{{ $p->is_published?'success':'secondary' }}">{{ $p->is_published ? 'published' : 'unpublished' }}</span></td>
            <td>
                <form action="{{ route('storefront-admin.products.toggle-publish', $p) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-primary">{{ $p->is_published ? 'Unpublish' : 'Publish' }}</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
