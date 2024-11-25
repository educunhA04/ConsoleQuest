<head>
    <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
</head>

<div class="orders-section">
    <h2>My Orders</h2>

    <!-- Loop through orders -->
    @foreach ($orders as $order)
    <div class="orders-container">
        <!-- Each order block -->
        <div class="order-block" onclick="openOrderDetails('{{ $order->tracking_number }}', '{{ $order->buy_date->format('Y-m-d') }}', '{{ ucfirst($order->status) }}', '€{{ number_format($order->products->sum(function($item) { return $item->quantity * $item->price; }), 2) }}', @json($order->products->pluck('product.name')))">
            <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
            <p><strong>Date:</strong> {{ $order->buy_date->format('Y-m-d') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
    </div>
    @endforeach

    <!-- Modal Popup -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeOrderDetails()">&times;</span>
            <h2>Order Details</h2>
            <p><strong>Tracking ID:</strong> <span id="modalTrackingId"></span></p>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>
            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            <p><strong>Total:</strong> <span id="modalTotal"></span></p>
            <p><strong>Products:</strong></p>
            <ul id="modalProducts"></ul>
        </div>
    </div>
</div>

<script>
    function openOrderDetails(trackingId, date, status, total, products) {
        // Update modal content dynamically
        document.getElementById('modalTrackingId').textContent = trackingId;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalStatus').textContent = status;
        document.getElementById('modalTotal').textContent = total;

        // Clear and update the products list
        const productsList = document.getElementById('modalProducts');
        productsList.innerHTML = ''; // Clear previous list
        products.forEach(product => {
            const listItem = document.createElement('li');
            listItem.textContent = product;
            productsList.appendChild(listItem);
        });

        // Display the modal
        document.getElementById('orderModal').style.display = 'block';
    }

    function closeOrderDetails() {
        // Hide the modal
        document.getElementById('orderModal').style.display = 'none';
    }
</script>