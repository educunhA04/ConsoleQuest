@extends('layouts.app')

@section('content')


<div class="static-page">
    <div class="staticpage-header">
        <h1>Frequently Asked Questions</h1>
        <p>Here you'll find answers to the most common questions about our products and services.</p>
    </div>
    <div class="staticpage-content">
        <div class="staticpage-item">
            <h2>What products do you sell?</h2>
            <p>We sell a wide range of games, consoles, and controllers from various brands, including PlayStation, Xbox, and Nintendo.</p>
        </div>
        <div class="staticpage-item">
            <h2>How can I place an order?</h2>
            <p>Simply browse our catalog, add the desired items to your cart, and proceed to checkout. You'll need to create an account or log in to complete your purchase.</p>
        </div>
        <div class="staticpage-item">
            <h2>What payment methods do you accept?</h2>
            <p>We accept major credit cards. You'll see it during checkout.</p>
        </div>
        <div class="staticpage-item">
            <h2>How long does shipping take?</h2>
            <p>Shipping times depend on your location. Typically, orders are delivered within 3-7 business days. Expedited shipping options are available at checkout.</p>
        </div>
        <div class="staticpage-item">
            <h2>What should I do if my order arrives damaged?</h2>
            <p>If your order arrives damaged, please contact our support team immediately with photos of the damage, and we'll assist you with a replacement or refund.</p>
        </div>
        <div class="staticpage-item">
            <h2>Do you offer warranties on your products?</h2>
            <p>Yes, all consoles and controllers come with a standard manufacturer's warranty.</p>
        </div>
        <div class="staticpage-item">
            <h2>How can I contact customer support?</h2>
            <p>You can reach our support team via email at support@gamestore.com or through our <a href="{{ route('home.aboutus') }}">About Us</a> page.</p>
        </div>
    </div>
</div>

@endsection
