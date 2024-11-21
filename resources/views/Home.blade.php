@extends('layouts.app')
@section('content')
<h1>Products</h1>

    @if ($products->isEmpty())
        <p>No products found matching your search.</p>
    @else
    <div class="product-grid">
        @foreach ($products as $product)
            <div class="product-container">
                <!-- Product Image -->
                <img src="{{ $product->image ?? 'https://via.placeholder.com/150' }}" alt="{{ $product->name }}" class="product-image">

                <!-- Product Name -->
                <div class="product-name">{{ $product->name }}</div>

                <!-- Product Price -->
                <div class="product-price">{{ number_format($product->price, 2, ',', '') }}€</div>
            </div>
        @endforeach
    </div>


    @endif
@endsection
