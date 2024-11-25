<head>
    <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
</head>

<div class="orders-section">
    <h2>My Orders</h2>

    <div class="orders-container">
        @foreach ($orders as $order)
        <div class="order-block">
            <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
            <p><strong>Date:</strong> {{ $order->buy_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
        @endforeach
    </div>
</div>