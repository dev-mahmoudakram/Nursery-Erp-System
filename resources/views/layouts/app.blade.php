<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', __('messages.app_name'))</title>
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    <style>
        body { font-family: 'Tahoma', 'Segoe UI', sans-serif; background:#f5f7f6; }
        .navbar-brand { font-weight:700; }
        .sidebar { min-height: calc(100vh - 56px); background:#1f4e3d; }
        .sidebar a { color:#e7f2ee; }
        .sidebar a.active, .sidebar a:hover { background:#173b2e; color:#fff; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark" style="background:#173b2e;">
    <div class="container-fluid d-flex justify-content-between">
        <span class="navbar-brand">🌱 {{ __('messages.app_name') }}</span>
        <div>
            @auth
                <span class="badge bg-light text-dark">{{ auth()->user()->name }} — {{ auth()->user()->role }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-light">{{ __('messages.auth.logout') }}</button>
                </form>
            @endauth
            <a href="{{ route('lang.switch', 'ar') }}" class="btn btn-sm {{ app()->getLocale()==='ar' ? 'btn-light' : 'btn-outline-light' }}">AR</a>
            <a href="{{ route('lang.switch', 'en') }}" class="btn btn-sm {{ app()->getLocale()==='en' ? 'btn-light' : 'btn-outline-light' }}">EN</a>
        </div>
    </div>
</nav>
<div class="d-flex">
    <div class="sidebar p-3" style="width:220px;">
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">📊 {{ __('messages.dashboard.title') }}</a>
        <hr class="text-white-50">
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('nurseries.*') ? 'active' : '' }}" href="{{ route('nurseries.index') }}">{{ __('messages.nav.nurseries') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}">{{ __('messages.nav.items') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('batches.*') ? 'active' : '' }}" href="{{ route('batches.index') }}">{{ __('messages.nav.batches') }}</a>
        <hr class="text-white-50">
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">{{ __('messages.nav.customers') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('opportunities.*') ? 'active' : '' }}" href="{{ route('opportunities.index') }}">{{ __('messages.nav.opportunities') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('quotations.*') ? 'active' : '' }}" href="{{ route('quotations.index') }}">{{ __('messages.nav.quotations') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('sales-orders.*') ? 'active' : '' }}" href="{{ route('sales-orders.index') }}">{{ __('messages.nav.sales_orders') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">{{ __('messages.nav.invoices') }}</a>
        <hr class="text-white-50">
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">{{ __('messages.nav.suppliers') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('purchase-requests.*') ? 'active' : '' }}" href="{{ route('purchase-requests.index') }}">{{ __('messages.nav.purchase_requests') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">{{ __('messages.nav.purchase_orders') }}</a>
        <hr class="text-white-50">
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('delivery-orders.*') ? 'active' : '' }}" href="{{ route('delivery-orders.index') }}">{{ __('messages.nav.delivery_orders') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">{{ __('messages.nav.vehicles') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('drivers.*') ? 'active' : '' }}" href="{{ route('drivers.index') }}">{{ __('messages.nav.drivers') }}</a>
        <hr class="text-white-50">
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('b2b-contracts.*') ? 'active' : '' }}" href="{{ route('b2b-contracts.index') }}">{{ __('messages.nav.b2b_contracts') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('government-tenders.*') ? 'active' : '' }}" href="{{ route('government-tenders.index') }}">{{ __('messages.nav.government_tenders') }}</a>
        <hr class="text-white-50">
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('storefront-admin.products.*') ? 'active' : '' }}" href="{{ route('storefront-admin.products.index') }}">{{ __('messages.nav.storefront_products') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('storefront-admin.coupons.*') ? 'active' : '' }}" href="{{ route('storefront-admin.coupons.index') }}">{{ __('messages.nav.coupons') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('storefront-admin.orders.*') ? 'active' : '' }}" href="{{ route('storefront-admin.orders.index') }}">{{ __('messages.nav.online_orders') }}</a>
        <a class="d-block p-2 rounded mb-1 {{ request()->routeIs('storefront-admin.reviews.*') ? 'active' : '' }}" href="{{ route('storefront-admin.reviews.index') }}">{{ __('messages.nav.reviews') }}</a>
    </div>
    <div class="flex-fill p-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</div>
</body>
</html>
