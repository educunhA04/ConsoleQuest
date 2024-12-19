@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/pages/checkout.css') }}">

<div class="checkout-container">
    <h2>Checkout</h2>
    <div class="checkout-items">
        @foreach ($cartItems as $cartItem)
        <div class="checkout-item">
            <img src="{{ asset('storage/' . $cartItem->product->images->first()->url) }}" alt="{{ $cartItem->product->name }}" class="checkout-item-image">
            <div class="checkout-item-details">
                <h4>{{ $cartItem->product->name }}</h4>
                <p>Quantity: {{ $cartItem->quantity }}</p>
                <p>Price: €{{ number_format($cartItem->product->price, 2) }}</p>
                <p><strong>Total: €{{ number_format($cartItem->quantity * $cartItem->product->price, 2) }}</strong></p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Resumo do Pedido -->
    <div class="checkout-summary">
        <h3>Total amount: €{{ number_format($totalPrice, 2) }}</h3>
    </div>

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
        
        <div class="form-group">
            <label for="shipping_address">Endereço de Envio:</label>
            <div class="checkout-input-container">
                @php
                $shippingAddress = Auth::user()->shippingAddress;
                @endphp
                <div class="form-row">
                    <label for="address">Address:</label>
                    <input 
                        value="{{ $shippingAddress->address ?? '' }}" 
                        type="text" 
                        id="address" 
                        name="address" 
                        class="checkout-input" 
                        required>
                </div>
                <div class="form-row">
                    <label for="postal_code">Postal Code:</label>
                    <input 
                        value="{{ $shippingAddress->postal_code ?? '' }}" 
                        type="text" 
                        id="postal_code" 
                        name="postal_code" 
                        class="checkout-input" 
                        required>
                </div>
                <div class="form-row">
                    <label for="location">Location:</label>
                    <input 
                        value="{{ $shippingAddress->location ?? '' }}" 
                        type="text" 
                        id="location" 
                        name="location" 
                        class="checkout-input" 
                        required>
                </div>
                <div class="form-row">
                    <label for="country">Country:</label>
                    <input 
                        value="{{ $shippingAddress->country ?? '' }}" 
                        type="text" 
                        id="country" 
                        name="country" 
                        class="checkout-input" 
                        required>
                </div>
            </div>
        </div>


       
        <div class="form-group">
            <label for="NIF">NIF:</label>
            <div class="checkout-input-container">
                <input type="text" name="NIF" id="NIF">
                <span class="checkout-tooltip-icon" data-tooltip="O NIF deve conter 9 dígitos numéricos.">?</span>
            </div>
        </div>

        
        
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
                <input 
                    type="text" 
                    name="credit_card_exp_date" 
                    id="credit_card_exp_date" 
                    placeholder="MM/AAAA" 
                    required 
                    maxlength="7" 
                    pattern="^(0[1-9]|1[0-2])\/\d{4}$" 
                    title="Insira a data no formato MM/AAAA."
                >
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

        
        <button type="submit" class="checkout-btn">Finalizar Compra</button>
    </form>

    

   
</div>
<script>
document.getElementById('credit_card_exp_date').addEventListener('input', function (event) {
    let input = event.target.value;

    // Remove any non-numeric characters except "/"
    input = input.replace(/[^0-9\/]/g, '');

    // Automatically insert "/" after the second character
    if (input.length === 2 && !input.includes('/')) {
        input = input + '/';
    }

    // Limit to MM/AAAA format (7 characters max)
    if (input.length > 7) {
        input = input.slice(0, 7);
    }

    // Update the input field value
    event.target.value = input;
});
</script>

@endsection

