@extends('layouts.app')
@section('title', $order->po_number)
@section('content')
<h4 class="mb-3">{{ __('messages.purchase_order.number') }} {{ $order->po_number }} — {{ $order->supplier->name_ar }}
    <span class="badge bg-secondary">{{ $order->status }}</span>
</h4>
<p class="text-muted">{{ __('messages.purchase_order.destination_nursery') }}: {{ $order->destinationNursery->name_ar }}</p>

<div class="card mb-4">
    <form action="{{ route('purchase-orders.receive', $order) }}" method="POST">
        @csrf
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('messages.purchase_order.item') }}</th><th>{{ __('messages.purchase_order.quantity') }}</th>
                    <th>{{ __('messages.purchase_order.unit_cost') }}</th><th>{{ __('messages.purchase_order.received_quantity') }}</th>
                    <th>{{ __('messages.purchase_order.remaining_quantity') }}</th><th>{{ __('messages.purchase_order.receive_goods') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->item->name_ar }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_cost }}</td>
                    <td>{{ $item->received_quantity }}</td>
                    <td>{{ $item->remaining_quantity }}</td>
                    <td>
                        @if($item->remaining_quantity > 0)
                            <input type="hidden" name="lines[{{ $loop->index }}][purchase_order_item_id]" value="{{ $item->id }}">
                            <input type="number" step="0.01" name="lines[{{ $loop->index }}][quantity_received]" class="form-control form-control-sm mb-1" max="{{ $item->remaining_quantity }}" placeholder="0">
                            <div class="form-check">
                                <input type="checkbox" name="lines[{{ $loop->index }}][quality_ok]" value="1" checked class="form-check-input">
                                <label class="form-check-label small">{{ __('messages.purchase_order.quality_ok') }}</label>
                            </div>
                        @else
                            <span class="text-success">✓</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-3">
            <button class="btn btn-success">{{ __('messages.purchase_order.receive_goods') }}</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">{{ __('messages.batch.movements_log') }} — Goods Receipts</div>
    <table class="table mb-0">
        <thead class="table-light"><tr><th>Date</th><th>Batch</th><th>Qty</th><th>{{ __('messages.purchase_order.quality_ok') }}</th></tr></thead>
        <tbody>
        @forelse($order->goodsReceipts as $receipt)
            @foreach($receipt->receiptItems as $ri)
                <tr>
                    <td>{{ $receipt->received_at }}</td>
                    <td>{{ $ri->batch?->batch_number ?? '—' }}</td>
                    <td>{{ $ri->quantity_received }}</td>
                    <td>{{ $ri->quality_ok ? '✓' : '✗' }}</td>
                </tr>
            @endforeach
        @empty
            <tr><td colspan="4" class="text-center text-muted">—</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
