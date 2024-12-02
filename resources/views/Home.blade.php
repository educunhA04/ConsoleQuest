@extends('layouts.app')
@section('content')
<h1>Products</h1>

    @if ($products->isEmpty())
        <p>No products found matching your search.</p>
    @else
    <div class="product-grid">
        @foreach ($products as $product)
        <div class="product-container">
            <div class="icon-container">
                <form action="{{ route('wishlist.add') }}" method="POST" class="add-to-wishlist-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="icon hear-icon" aria-label="Add to wishlist">
                        &#x2661;
                    </button>
                </form>
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1"> 
                    <button type="submit" class="icon cart-icon" aria-label="Add to cart">
                        &#x1F6D2; 
                    </button>
                </form>

            </div>
            <a href="{{ route('product.show', ['id' => $product->id]) }}" class="product-link">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">${{ number_format($product->price, 2) }}</div>
            </a>


        </div>
        @endforeach
    </div>


    @endif
@endsection
