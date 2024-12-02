@extends('layouts.app')
@section('content')
<div class="product-grid">
    @foreach ($wishlistItems as $wishlistItem)
        <div class="product-container">
            <a class="product-link">
                <img src="{{ asset('storage/' . $wishlistItem->product->image) }}"alt="{{ $wishlistItem->product->name }}" class="product-image">
                <div class="product-name">{{ $wishlistItem->product->name }}</div>
                <div class="product-price">${{ number_format($wishlistItem->product->price, 2) }}</div>
                <div class="cart-item-quantity">Quantity: {{ $wishlistItem->quantity }}</div>
            </a>
        </div>
    @endforeach
</div>
@endsection
