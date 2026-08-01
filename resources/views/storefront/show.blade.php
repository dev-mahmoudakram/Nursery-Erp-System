@extends('storefront.layout')
@section('content')
<div class="row">
    <div class="col-md-5">
        @if($product->cover_image_path)
            <img src="{{ asset('storage/' . $product->cover_image_path) }}" class="img-fluid rounded">
        @else
            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height:300px;font-size:5rem;">🌿</div>
        @endif
    </div>
    <div class="col-md-7">
        <h3>{{ app()->getLocale()==='en' && $product->display_name_en ? $product->display_name_en : ($product->display_name_ar ?? $product->item->name_ar) }}</h3>
        <p class="text-muted">{{ $product->item->scientific_name }}</p>
        <h4 class="text-success">{{ $product->item->retail_price }} SAR</h4>
        <p>{{ app()->getLocale()==='en' ? $product->description_en : $product->description_ar }}</p>
        <p>{{ __('messages.storefront.live_stock') }}: <strong>{{ $product->live_stock }}</strong></p>

        @if($product->live_stock > 0)
        <form action="{{ route('storefront.cart.add', $product) }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="number" name="quantity" value="1" min="1" max="{{ $product->live_stock }}" step="1" class="form-control" style="max-width:120px">
            <button class="btn btn-success">{{ __('messages.storefront.add_to_cart') }}</button>
        </form>
        @else
            <p class="text-danger">{{ __('messages.storefront.out_of_stock') }}</p>
        @endif
    </div>
</div>

<hr class="my-4">
<h5>{{ __('messages.storefront.reviews') }}</h5>
@forelse($reviews as $r)
    <div class="border-bottom py-2">
        <strong>{{ $r->customer_name }}</strong> — {{ str_repeat('⭐', $r->rating) }}
        <p class="mb-0">{{ $r->comment }}</p>
    </div>
@empty
    <p class="text-muted">—</p>
@endforelse

<div class="card p-3 mt-3">
    <h6>{{ __('messages.storefront.write_review') }}</h6>
    <form action="{{ route('storefront.review.store', $product) }}" method="POST" class="row g-2">
        @csrf
        <div class="col-md-4"><input name="customer_name" class="form-control" placeholder="{{ __('messages.storefront.your_name') }}" required></div>
        <div class="col-md-2">
            <select name="rating" class="form-select">
                @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} ⭐</option>@endfor
            </select>
        </div>
        <div class="col-md-4"><input name="comment" class="form-control" placeholder="{{ __('messages.storefront.comment') }}"></div>
        <div class="col-md-2"><button class="btn btn-outline-success w-100">{{ __('messages.storefront.submit_review') }}</button></div>
    </form>
</div>
@endsection
