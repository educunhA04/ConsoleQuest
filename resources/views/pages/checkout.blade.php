@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/checkout.css') }}">

<div class="checkout-container">
    <h2>Finalizar Compra</h2>
    <div class="checkout-items">
        @foreach ($cartItems as $cartItem)
        <div class="checkout-item">
            <img src="{{ asset('storage/' . $cartItem->product->image) }}" alt="{{ $cartItem->product->name }}" class="checkout-item-image">
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

    @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <form method="POST" action="{{ route('checkout.finalize') }}">
        @csrf

        <!-- Campo NIF -->
        <div class="form-group">
            <label for="NIF">NIF:</label>
            <div class="checkout-input-container">
                <input type="text" name="NIF" id="NIF">
                <span class="checkout-tooltip-icon" data-tooltip="O NIF deve conter 9 dígitos numéricos.">?</span>
            </div>
        </div>

        <!-- Informações do Cartão de Crédito -->
        
        <div class="form-group">
            <label for="credit_card_number">Número do Cartão:</label>
            <div class="checkout-input-container">
                <input type="text" name="credit_card_number" id="credit_card_number" required>
                <span class="checkout-tooltip-icon" data-tooltip="O número do cartão deve conter 16 dígitos numéricos.">?</span>
            </div>
        </div>

        <div class="form-group">
            <label for="credit_card_exp_date">Data de Validade:</label>
            <div class="checkout-input-container">
                <input type="date" name="credit_card_exp_date" id="credit_card_exp_date" required>
                <span class="checkout-tooltip-icon" data-tooltip="Insira a data de validade no formato MM/AAAA.">?</span>
            </div>
        </div>

    
        <div class="form-group">
            <label for="credit_card_cvv">CVV:</label>
            <div class="checkout-input-container">
                <input type="text" name="credit_card_cvv" id="credit_card_cvv" required>
                <span class="checkout-tooltip-icon" data-tooltip="O CVV deve conter 3 dígitos numéricos.">?</span>
            </div>
        </div>

        <!-- Botão para Finalizar -->
        <button type="submit" class="checkout-btn">Finalizar Compra</button>
    </form>

  


    

   
</div>
@endsection
