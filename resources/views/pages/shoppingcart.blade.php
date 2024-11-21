@extends('layouts.app')
@section('content')

<div class="checkout-page">
    <header class="checkout-header">
        <link rel="stylesheet" href="{{ asset('css/pages/shoppingcart.css') }}">
        <nav class="progress-bar">
            <span class="progress-step active">Resumo</span>
            <span class="progress-step">Identificação</span>
            <span class="progress-step">Pagamento</span>
            <span class="progress-step">Imprimir Recibo</span>
        </nav>
    </header>

    <div class="cart-section">
        <h1>As tuas compras</h1>
        <p>Confirma os produtos e quantidades da tua encomenda</p>
        <div class="product-row" data-product-id="1">
            <div class="product-details">
                <img src="placeholder.jpg" alt="Lorem Ipsum" class="product-image">
                <div class="product-info">
                    <div class="product-name">Lorem Ipsum</div>
                    <div class="product-attributes">
                        <span>Ref: #000000</span><br>
                    </div>
                </div>
            </div>
            <div class="product-actions">
                <button class="btn-quantity btn-decrease">-</button>
                <input type="number" value="1" min="1" class="quantity-input" id="quantity-1">
                <button class="btn-quantity btn-increase">+</button>
                <div class="product-links">
                    <a href="#">Remover</a> |
                    <a href="#">Guardar nos favoritos</a> |
                    <a href="#">Adicionar mensagem personalizada</a>
                </div>
            </div>
            <div class="product-price">0,00€</div>
        </div>

        <div class="promo-section">
            <h2>Código promocional</h2>
            <div class="promo-input">
                <input type="text" placeholder="Introduz o teu código">
                <button class="btn-apply">APLICAR</button>
            </div>
        </div>
    </div>

    <div class="summary-section">
        <div class="summary-item">
            <span>VALOR DOS PRODUTOS</span>
            <span>0,00€</span>
        </div>
        <div class="summary-item">
            <span>Entrega em casa - CTT</span>
            <span>0,00€</span>
        </div>
        <div class="summary-total">
            <span>TOTAL DA COMPRA</span>
            <span>0,00€</span>
        </div>
        <button class="btn-continue">CONTINUAR</button>
    </div>
</div>



@endsection

