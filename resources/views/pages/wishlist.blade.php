@extends('layouts.app')

@section('content')
<div class="product-grid">
    @if ($wishlistItems->isEmpty())
        <p>Your wishlist is empty!</p>
    @else
        @foreach ($wishlistItems as $wishlistItem)
            <div class="product-container">
                <a href="{{ route('product.show', $wishlistItem->product->id) }}" class="product-link">
                    <div class="product-image-wrapper">
                        <img src="{{ asset('storage/' . $wishlistItem->product->images->first()->url) }}" alt="{{ $wishlistItem->product->name }}" class="product-image">                
                    </div>
                    <div class="product-name">{{ $wishlistItem->product->name }}</div>
                    <div class="product-price">${{ number_format($wishlistItem->product->price, 2) }}</div>
                </a>
                <div class="product-actions-wishlist">
                <form action="{{ route('wishlist.remove', $wishlistItem->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="wishlist-btn-danger">Remove from Wishlist</button>
                    </form>
                    <button 
                    class="wishlist-btn-primary"
                    aria-label="Add to cart"
                    onclick="addToCart({{ $wishlistItem->product_id }}, 1)">
                    Add to Cart
                 </button>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection