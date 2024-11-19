@extends('layouts.app')
@section('content')
<div class="product-grid">
    @foreach ($products as $product)
        <div class="product-container">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-price">${{ number_format($product->price, 2) }}</div>
        </div>
    @endforeach
</div>

@endsection
