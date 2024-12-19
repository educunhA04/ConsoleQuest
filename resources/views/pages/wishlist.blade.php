@extends('layouts.app')

@section('content')
<div class="product-grid">
    @if ($wishlistItems->isEmpty())
        <p>Your wishlist is empty!</p>
    @else
        @foreach ($wishlistItems as $wishlistItem)
            <div class="product-container">
                <a href="{{ route('product.show', $wishlistItem->product->id) }}" class="product-link">
                 <img src="{{ asset('storage/' . $wishlistItem->product->images->first()->url) }}" alt="{{ $wishlistItem->product->name }}" class="product-image">                
                    <div class="product-name">{{ $wishlistItem->product->name }}</div>
                    <div class="product-price">${{ number_format($wishlistItem->product->price, 2) }}</div>
                    <div class="cart-item-quantity">Quantity: {{ $wishlistItem->quantity }}</div>
                </a>
                <div class="product-actions">
                    <form action="{{ route('wishlist.remove', $wishlistItem->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Remove from Wishlist</button>
                    </form>
                    <button 
                    class="btn btn-primary"
                    aria-label="Add to cart"
                    onclick="addToCart({{ $wishlistItem->id }}, 1)">
                    Add to Cart
                 </button>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection