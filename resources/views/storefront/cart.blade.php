@extends('storefront.layout')
@section('content')
<h4 class="mb-3">{{ __('messages.storefront.cart') }}</h4>
@if($cart->items->isEmpty())
    <p class="text-muted">{{ __('messages.storefront.empty_cart') }}</p>
    <a href="{{ route('storefront.index') }}" class="btn btn-success">{{ __('messages.storefront.title') }}</a>
@else
    <div class="card mb-4">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th></th><th>{{ __('messages.storefront.quantity') }}</th><th>{{ __('messages.storefront.price') }}</th><th>{{ __('messages.storefront.subtotal') }}</th><th></th></tr>
            </thead>
            <tbody>
            @foreach($cart->items as $ci)
                <tr>
                    <td>{{ $ci->item->name_ar }}</td>
                    <td>{{ $ci->quantity }}</td>
                    <td>{{ $ci->item->retail_price }}</td>
                    <td>{{ $ci->quantity * $ci->item->retail_price }}</td>
                    <td>
                        <form action="{{ route('storefront.cart.remove', $ci) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">{{ __('messages.storefront.remove') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <h5>{{ __('messages.storefront.total') }}: {{ $cart->subtotal }} SAR</h5>
    <a href="{{ route('storefront.checkout') }}" class="btn btn-success">{{ __('messages.storefront.checkout') }}</a>
@endif
@endsection
