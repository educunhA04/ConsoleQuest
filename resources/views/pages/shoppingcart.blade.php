@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/shoppingcart.css') }}">

<div class="cart-container">
    <div class="cart-header">As tuas compras</div>
    <div class="cart-grid">
        @if ($cartItems->isEmpty())
            <p>Your shopping cart is empty!</p>
        @else
            @foreach ($cartItems as $cartItem)
                @php
                    $product = is_array($cartItem) ? $cartItem['product'] : $cartItem->product;
                @endphp

                <div class="cart-item-container">
                    <a href="{{ route('product.show', $product->id) }}" class="product-link">
                    <img src="{{ asset('storage/' . $product->images->first()->url) }}" alt="{{ $product->name }}" class="product-image">                
                    <h4 class="cart-item-name">{{ $product->name }}</h4>
                    </a>
                    <p class="cart-item-price">Preço Unitário: €{{ number_format($product->price, 2) }}</p>
                    <p class="cart-item-quantity">Quantidade: {{ is_array($cartItem) ? $cartItem['quantity'] : $cartItem->quantity }}</p>
                    <p><strong>Total do Produto:</strong> €{{ number_format((is_array($cartItem) ? $cartItem['quantity'] : $cartItem->quantity) * $product->price, 2) }}</p>
                    <div class="cart-item-actions">
                        <form method="POST" action="{{ route('cart.update') }}">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ is_array($cartItem) ? $cartItem['product']->id : $cartItem->id }}">
                            <input type="number" name="quantity" value="{{ is_array($cartItem) ? $cartItem['quantity'] : $cartItem->quantity }}" min="1" style="width: 50px;">
                            <button type="submit">Atualizar</button>
                        </form>
                        <form method="POST" action="{{ route('cart.remove') }}">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ is_array($cartItem) ? $cartItem['product']->id : $cartItem->id }}">
                            <button type="submit">Remover</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="cart-summary">
        <h3>Total dos Produtos: <span class="total-price">€{{ number_format($totalPrice, 2) }}</span></h3>
    </div>

    <div class="cart-buttons">
        <form method="POST" action="{{ route('cart.clear') }}">
            @csrf
            <button class="clear-cart" type="submit">Limpar Carrinho</button>
        </form>
    </div>
    <div class="cart-checkout">
        @if (Auth::check())
            <a href="{{ route('cart.checkout') }}" class="checkout-btn">Continuar</a>
        @else
            <a href="{{ route('login') }}" class="checkout-btn">Faça login para continuar</a>
        @endif
    </div>
</div>
@endsection
