@extends('storefront.layout')
@section('content')
<h4 class="mb-3">{{ __('messages.storefront.checkout') }}</h4>

<div class="row">
    <div class="col-md-7">
        <form action="{{ route('storefront.checkout.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.customer_name') }}</label><input name="customer_name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.phone') }}</label><input name="phone" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.email') }}</label><input name="email" type="email" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.city') }}</label><input name="city" class="form-control"></div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('messages.storefront.nursery') }}</label>
                    <select name="nursery_id" class="form-select" required>
                        @foreach($nurseries as $n)<option value="{{ $n->id }}">{{ $n->name_ar }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('messages.storefront.delivery_method') }}</label>
                    <select name="delivery_method" id="deliveryMethod" class="form-select" onchange="document.getElementById('addrWrap').style.display = this.value==='delivery' ? 'block':'none'">
                        <option value="delivery">{{ __('messages.storefront.delivery') }}</option>
                        <option value="pickup">{{ __('messages.storefront.pickup') }}</option>
                    </select>
                </div>
                <div class="col-md-12" id="addrWrap">
                    <label class="form-label">{{ __('messages.storefront.delivery_address') }}</label>
                    <input name="delivery_address" class="form-control">
                </div>
                <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.requested_date') }}</label><input type="date" name="requested_delivery_date" class="form-control"></div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('messages.storefront.payment_method') }}</label>
                    <select name="payment_method" class="form-select">
                        <option value="online">{{ __('messages.storefront.pay_online') }}</option>
                        <option value="cash_on_delivery">{{ __('messages.storefront.cash_on_delivery') }}</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">{{ __('messages.storefront.coupon_code') }}</label><input name="coupon_code" class="form-control"></div>
            </div>
            <button class="btn btn-success mt-4">{{ __('messages.storefront.place_order') }}</button>
        </form>
    </div>

    <div class="col-md-5">
        <div class="card p-3">
            <h6>{{ __('messages.storefront.cart') }}</h6>
            @foreach($cart->items as $ci)
                <div class="d-flex justify-content-between">
                    <span>{{ $ci->item->name_ar }} × {{ $ci->quantity }}</span>
                    <span>{{ $ci->quantity * $ci->item->retail_price }}</span>
                </div>
            @endforeach
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>{{ __('messages.storefront.total') }}</span>
                <span>{{ $cart->subtotal }} SAR</span>
            </div>
        </div>
    </div>
</div>
@endsection
