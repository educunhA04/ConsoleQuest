@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/checkout.css') }}">

<div class="checkout-container">
    <h2>Finalizar Compra</h2>

    <!-- Itens no Checkout -->
    <div class="checkout-items">
        @foreach ($cartItems as $cartItem)
        <div class="checkout-item">
            <img src="{{ $cartItem->product->image }}" alt="{{ $cartItem->product->name }}" class="checkout-item-image">
            <div class="checkout-item-details">
                <h4>{{ $cartItem->product->name }}</h4>
                <p>Quantidade: {{ $cartItem->quantity }}</p>
                <p>Preço Unitário: €{{ number_format($cartItem->product->price, 2) }}</p>
                <p><strong>Total: €{{ number_format($cartItem->quantity * $cartItem->product->price, 2) }}</strong></p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Resumo do Pedido -->
    <div class="checkout-summary">
        <h3>Total a Pagar: €{{ number_format($totalPrice, 2) }}</h3>
    </div>

    <!-- Botão para Finalizar -->
    <form method="POST" action="{{ route('checkout.finalize') }}">
        @csrf
        <button type="submit" class="checkout-btn">Finalizar Compra</button>
    </form>
</div>
@endsection
