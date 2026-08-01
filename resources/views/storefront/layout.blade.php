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
    <style>body{background:#f8faf9;}</style>
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
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</div>
</body>
</html>
