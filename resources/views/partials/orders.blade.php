<head>
    <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
</head>

<div class="orders-section">
    <h2>My Orders</h2>

    <!-- Loop through orders -->
    @foreach ($orders as $order)
    <div class="orders-container">
        <!-- Each order block -->
        <div class="order-block">
            <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
            <p><strong>Date:</strong> {{ $order->buy_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
    </div>
    @endforeach
</div>