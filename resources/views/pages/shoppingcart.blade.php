@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/shoppingcart.css') }}">

<div class="cart-container">
    <div class="cart-header">As tuas compras</div>
    <div class="cart-grid">
        @foreach ($cartItems as $cartItem)
        <div class="cart-item-container">
            <img src="{{ asset('storage/' . $cartItem->product->image) }}" alt="{{ $cartItem->product->name }}" class="cart-item-image">
            <h4 class="cart-item-name">{{ $cartItem->product->name }}</h4>
            <p class="cart-item-price">Preço Unitário: €{{ number_format($cartItem->product->price, 2) }}</p>
            <p class="cart-item-quantity">Quantidade: {{ $cartItem->quantity }}</p>
            <p><strong>Total do Produto:</strong> €{{ number_format($cartItem->quantity * $cartItem->product->price, 2) }}</p>
            <div class="cart-item-actions">
                <!-- Atualizar Quantidade -->
                <form method="POST" action="{{ route('cart.update') }}">
                    @csrf
                    <input type="hidden" name="cart_item_id" value="{{ $cartItem->id }}">
                    <input type="number" name="quantity" value="{{ $cartItem->quantity }}" min="1" style="width: 50px;">
                    <button type="submit">Atualizar</button>
                </form>
                <!-- Remover Produto -->
                <form method="POST" action="{{ route('cart.remove') }}">
                    @csrf
                    <input type="hidden" name="cart_item_id" value="{{ $cartItem->id }}">
                    <button type="submit">Remover</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Resumo -->
    <div class="cart-summary">
        <h3>Total dos Produtos: <span class="total-price">€{{ number_format($totalPrice, 2) }}</span></h3>
    </div>

    <!-- Botões de Ação -->
    <div class="cart-buttons">
        <form method="POST" action="{{ route('cart.clear') }}">
            @csrf
            <button class="clear-cart" type="submit">Limpar Carrinho</button>
        </form>
        <a href="{{ route('cart.checkout') }}" class="checkout">Continuar</a>
    </div>
        <div class="cart-checkout">
        <a href="{{ route('cart.checkout') }}" class="checkout-btn">Continuar</a>
    </div>

</div>
@endsection
