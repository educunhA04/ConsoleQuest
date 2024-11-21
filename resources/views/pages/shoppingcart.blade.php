@extends('layouts.app')
@section('content')
<div class="product-grid">
    @foreach ($cartItems as $product)
        <div class="product-container">
            <a  class="product-link">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">${{ number_format($product->price, 2) }}</div>
            </a>


        </div>
    @endforeach
</div>
@endsection