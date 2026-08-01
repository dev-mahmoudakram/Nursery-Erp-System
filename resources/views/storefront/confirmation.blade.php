@extends('storefront.layout')
@section('content')
<div class="text-center py-5">
    <h2 class="text-success">✓ {{ __('messages.storefront.order_confirmation_title') }}</h2>
    <p class="lead">{{ __('messages.storefront.order_number') }}: <strong>{{ $order->order_number }}</strong></p>
    <p class="text-muted">{{ __('messages.storefront.order_confirmation_note') }}</p>
    <a href="{{ route('storefront.index') }}" class="btn btn-success mt-3">{{ __('messages.storefront.title') }}</a>
</div>
@endsection
