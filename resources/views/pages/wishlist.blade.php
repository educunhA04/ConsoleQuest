@extends('layouts.app')

@section('content')
<div class="product-grid">
    @foreach ($wishlistItems as $wishlistItem)
        <div class="product-container">
            <a class="product-link">
                <img src="{{ asset('storage/' . $wishlistItem->product->image) }}" alt="{{ $wishlistItem->product->name }}" class="product-image">
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
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $wishlistItem->product->id }}">
                    <input type="hidden" name="quantity" value="1"> 
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection