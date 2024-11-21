@extends('layouts.app')
@section('content')
<div class="product-grid">
    @foreach ($products as $product)
        <div class="product-container">
            <div class="icon-container">
                <span class="icon heart-icon">&#x2661;</span>
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
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">${{ number_format($product->price, 2) }}</div>
            </a>


        </div>
    @endforeach
</div>
@endsection
