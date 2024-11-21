@extends('layouts.app')
@section('content')
<div class="product-grid">
    @foreach ($cartItems as $cartItem)
        <div class="product-container">
            <a class="product-link">
                <!-- Access the related product -->
                <img src="{{ $cartItem->product->image }}" alt="{{ $cartItem->product->name }}" class="product-image">
                <div class="product-name">{{ $cartItem->product->name }}</div>
                <div class="product-price">${{ number_format($cartItem->product->price, 2) }}</div>

                <!-- You can also access the cartItem's quantity or other properties -->
                <div class="cart-item-quantity">Quantity: {{ $cartItem->quantity }}</div>
            </a>
        </div>
    @endforeach
</div>
@endsection
