<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.storefront.title') }} - {{ __('messages.app_name') }}</title>
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    <style>
        body{background:#f8faf9;}
        .product-card{transition:.15s;height:100%;}
        .product-card:hover{box-shadow:0 4px 14px rgba(0,0,0,.08);}
    </style>
</head>
<body>
<nav class="navbar navbar-dark" style="background:#173b2e;">
    <div class="container d-flex justify-content-between">
        <a href="{{ route('storefront.index') }}" class="navbar-brand">🌱 {{ __('messages.app_name') }}</a>
        <div>
            <a href="{{ route('lang.switch', 'ar') }}" class="btn btn-sm {{ app()->getLocale()==='ar'?'btn-light':'btn-outline-light' }}">AR</a>
            <a href="{{ route('lang.switch', 'en') }}" class="btn btn-sm {{ app()->getLocale()==='en'?'btn-light':'btn-outline-light' }}">EN</a>
            <a href="{{ route('storefront.cart') }}" class="btn btn-sm btn-success">🛒 {{ __('messages.storefront.cart') }}</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form class="mb-4">
        <input class="form-control" name="q" placeholder="{{ __('messages.storefront.search_placeholder') }}" value="{{ request('q') }}">
    </form>

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-4 col-lg-3">
                <div class="card product-card">
                    @if($product->cover_image_path)
                        <img src="{{ asset('storage/' . $product->cover_image_path) }}" class="card-img-top" style="height:160px;object-fit:cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:160px;font-size:3rem;">🌿</div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">
                            {{ app()->getLocale()==='en' && $product->display_name_en ? $product->display_name_en : ($product->display_name_ar ?? $product->item->name_ar) }}
                        </h6>
                        <p class="mb-1 fw-bold">{{ $product->item->retail_price }} SAR</p>
                        <p class="small text-success mb-2">{{ __('messages.storefront.in_stock') }}: {{ $product->live_stock }}</p>
                        <a href="{{ route('storefront.show', $product) }}" class="btn btn-outline-success btn-sm mt-auto">{{ __('messages.common.view') }}</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">—</p>
        @endforelse
    </div>
</div>
</body>
</html>
