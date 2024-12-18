@extends('layouts.app')

@section('content')


<div class="static-page">
    <div class="staticpage-header">
        <h1>Terms and Conditions</h1>
        <p>Effective Date: {{ date('F d, Y') }}</p>
    </div>

    <div class="staticpage-content">
        <div class="staticpage-item">
            <h2>1. Introduction</h2>
            <p>Welcome to our store! These Terms and Conditions govern your use of our website and services. By accessing or making a purchase, you agree to these terms. If you do not agree, please refrain from using our services.</p>
        </div>

        <div class="staticpage-item">
            <h2>2. Purchases and Payments</h2>
            <p>All purchases made on our platform are subject to product availability and pricing at the time of the order. Payment methods include credit/debit cards, PayPal, and other approved options.</p>
            <p>Prices are listed in EUR (€) and include VAT. Additional fees, such as shipping costs, will be displayed before checkout.</p>
        </div>

        <div class="staticpage-item">
            <h2>3. Shipping and Delivery</h2>
            <p>We strive to process and deliver orders promptly. Delivery times may vary based on location. Shipping costs and estimated delivery dates will be provided during checkout. Please ensure your shipping information is accurate to avoid delays.</p>
        </div>

        <div class="staticpage-item">
            <h2>4. Returns and Refunds</h2>
            <p>You can request a return within 14 days of receiving your order, provided the item is unused and in its original packaging. Refunds will be processed after inspection of the returned items. Shipping costs for returns are non-refundable.</p>
            <p>For digital purchases (e.g., game codes), all sales are final and non-refundable.</p>
        </div>

        <div class="staticpage-item">
            <h2>5. User Accounts</h2>
            <p>To make a purchase, you may need to create an account. You are responsible for safeguarding your account credentials. Any activity under your account is your responsibility.</p>
        </div>

        <div class="staticpage-item">
            <h2>6. Prohibited Activities</h2>
            <p>When using our website, you agree not to engage in any unlawful activities, including:</p>
            <ul>
                <li>Hacking, phishing, or distributing malicious software.</li>
                <li>Misrepresenting yourself or your account information.</li>
                <li>Violating intellectual property rights.</li>
            </ul>
        </div>

        <div class="staticpage-item">
            <h2>7. Limitation of Liability</h2>
            <p>Our store is not responsible for damages resulting from the misuse of our products or delays in delivery caused by circumstances beyond our control.</p>
        </div>

        <div class="staticpage-item">
            <h2>8. Changes to Terms</h2>
            <p>We reserve the right to modify these Terms and Conditions at any time. Changes will be effective immediately upon posting. Continued use of the website signifies your acceptance of the revised terms.</p>
        </div>

    
    </div>
</div>

@endsection
