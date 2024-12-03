@extends('layouts.app')
<head>
    <title>Console Quest - {{ $product->name }}</title> <!-- Use the product name for the title -->
    <link rel="stylesheet" href="{{ asset('css/pages/product.css') }}">
</head>
<body>
    @section('content')
    <div class="breadcrumb">
        Home / {{ $product->category->type ?? 'Categoria Desconhecida' }} / {{ $product->name }} <!-- Dynamically show category and product name -->
    </div>
    <div class="product-page">
        <div class="product-info">
            <h1 class="product-name">{{ $product->name }}</h1> <!-- Product name -->
            <div class="rating">
                ★★★★☆ <!-- You can replace this with dynamic ratings if needed -->
            </div>
            <img src="{{ $product->image }}"  alt="{{ $product->name }}" class="product-image"> <!-- Product image -->
        </div>

        <div class="product-details">
            <span class="wishlist-icon">♡</span>
            <div class="price">{{ number_format($product->price, 2) }} €</div> <!-- Product price -->
            <p class="description">{{ $product->description }}</p> <!-- Product description -->
            <button class="add-to-cart-btn">ADICIONAR AO CARRINHO</button>
        </div>
        
        <div class="reviews">
            <h3>Reviews 3.2/5 ★</h3>
            <div class="review">
                <strong>Filomena</strong> 4/5 ★
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hac sectione.</p>
            </div>
            <div class="review">
                <strong>Rui</strong> 3.5/5 ★
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hac sectione.</p>
            </div>
            <div class="review">
                <strong>Pedro</strong> 2/5 ★
                <p>Lorem ipsum dolor sit amet.</p>
            </div>
        </div>
    </div>

    @endsection

</body>
