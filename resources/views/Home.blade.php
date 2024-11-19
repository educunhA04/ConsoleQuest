@extends('layouts.app')
@section('content')
<div class="product-grid">
    @foreach ($products as $product)
        <div class="product-container">
        <div class="icon-container">
                <span class="icon heart-icon">&#x2661;</span>
                <span class="icon cart-icon">&#x1F6D2;</span> 
        </div>
            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-price">${{ number_format($product->price, 2) }}</div>
        </div>
    @endforeach
</div>

@endsection
