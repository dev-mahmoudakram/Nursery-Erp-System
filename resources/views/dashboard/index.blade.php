@extends('layouts.app')
@section('title', __('messages.dashboard.title'))
@section('content')
<h4 class="mb-4">{{ __('messages.dashboard.title') }}</h4>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small>{{ __('messages.dashboard.available_units') }}</small>
            <h3>{{ number_format($totalAvailableUnits) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center bg-success text-white">
            <small>{{ __('messages.dashboard.inventory_value') }}</small>
            <h3>{{ number_format($totalInventoryValue) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small>{{ __('messages.dashboard.sales_this_month') }}</small>
            <h3>{{ number_format($salesThisMonth) }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center {{ $overdueInvoices > 0 ? 'bg-danger text-white' : '' }}">
            <small>{{ __('messages.dashboard.overdue_invoices') }}</small>
            <h3>{{ $overdueInvoices }}</h3>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small>{{ __('messages.dashboard.pending_online_orders') }}</small>
            <h4>{{ $pendingOnlineOrders }}</h4>
            <a href="{{ route('storefront-admin.orders.index') }}" class="small">{{ __('messages.common.view') }}</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <small>{{ __('messages.dashboard.pending_purchase_orders') }}</small>
            <h4>{{ $pendingPurchaseOrders }}</h4>
            <a href="{{ route('purchase-orders.index') }}" class="small">{{ __('messages.common.view') }}</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <small>{{ __('messages.dashboard.tender_pipeline') }}</small>
            <div class="d-flex gap-2 flex-wrap mt-1">
                @forelse($tenderPipeline as $status => $count)
                    <span class="badge bg-secondary">{{ $status }}: {{ $count }}</span>
                @empty
                    <span class="text-muted small">—</span>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-danger">⚠ {{ __('messages.dashboard.low_stock_alert') }}</div>
            <table class="table mb-0">
                <thead class="table-light"><tr><th>{{ __('messages.item.title') }}</th><th>{{ __('messages.item.safety_stock') }}</th></tr></thead>
                <tbody>
                @forelse($lowStockItems as $item)
                    <tr><td>{{ $item->name_ar }}</td><td>{{ $item->safety_stock }}</td></tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">{{ __('messages.dashboard.no_alerts') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">🏆 {{ __('messages.dashboard.top_customers') }}</div>
            <table class="table mb-0">
                <thead class="table-light"><tr><th>{{ __('messages.customer.title') }}</th><th>{{ __('messages.order.total') }}</th></tr></thead>
                <tbody>
                @forelse($topCustomers as $c)
                    <tr><td>{{ $c->name_ar }}</td><td>{{ number_format($c->sales_orders_sum_total ?? 0) }}</td></tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">—</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
