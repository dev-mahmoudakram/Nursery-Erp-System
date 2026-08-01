<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.portal.title') }}</title>
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    @endif
    <style>body{background:#f5f7f6;}</style>
</head>
<body>
<nav class="navbar navbar-dark" style="background:#173b2e;">
    <div class="container d-flex justify-content-between">
        <span class="navbar-brand">🌱 {{ __('messages.portal.title') }}</span>
        <div>
            <a href="{{ route('lang.switch', 'ar') }}" class="btn btn-sm {{ app()->getLocale()==='ar'?'btn-light':'btn-outline-light' }}">AR</a>
            <a href="{{ route('lang.switch', 'en') }}" class="btn btn-sm {{ app()->getLocale()==='en'?'btn-light':'btn-outline-light' }}">EN</a>
            <form action="{{ route('portal.logout') }}" method="POST" class="d-inline">@csrf<button class="btn btn-sm btn-outline-light">{{ __('messages.portal.logout') }}</button></form>
        </div>
    </div>
</nav>
<div class="container py-4">
    <h4>{{ __('messages.portal.welcome') }}, {{ $customer->name_ar }}</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">{{ __('messages.portal.my_orders') }}</div>
        <table class="table mb-0">
            <thead class="table-light"><tr><th>{{ __('messages.order.number') }}</th><th>{{ __('messages.order.status') }}</th><th>{{ __('messages.order.total') }}</th><th></th></tr></thead>
            <tbody>
            @forelse($orders as $o)
                <tr>
                    <td>{{ $o->order_number }}</td>
                    <td><span class="badge bg-secondary">{{ $o->status }}</span></td>
                    <td>{{ $o->total }}</td>
                    <td>
                        <form action="{{ route('portal.reorder', $o) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">{{ __('messages.portal.reorder') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">—</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mb-4">{{ $orders->links() }}</div>

    <div class="card mb-4">
        <div class="card-header">{{ __('messages.portal.my_invoices') }}</div>
        <table class="table mb-0">
            <thead class="table-light"><tr><th>{{ __('messages.invoice.number') }}</th><th>{{ __('messages.invoice.status') }}</th><th>{{ __('messages.invoice.remaining') }}</th></tr></thead>
            <tbody>
            @forelse($invoices as $inv)
                <tr><td>{{ $inv->invoice_number }}</td><td><span class="badge bg-secondary">{{ $inv->status }}</span></td><td>{{ $inv->remaining }}</td></tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted">—</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $invoices->links() }}
</div>
</body>
</html>
