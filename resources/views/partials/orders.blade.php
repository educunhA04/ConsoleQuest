<div class="orders-section">
    <h2>My Orders</h2>

    <div class="orders-container">
    @foreach ($orders as $order)
        <div class="order-block">
            <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
            <p><strong>Date:</strong> {{ $order->buy_date }}</p>
            <p><strong>Status:</strong> <span id="status-{{ $order->id }}">{{ ucfirst($order->status) }}</span></p>
            @if ($order->status === 'processing')
                <button class="cancel-order-btn" onclick="confirmCancelOrder({{ $order->id }})">Cancel Order</button>
            @endif
        </div>
    @endforeach
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Are you sure you want to cancel this order?</h3>
        <div>
            <button class="confirm-btn" id="confirmCancel" onclick="">Yes</button>
            <button class="cancel-btn" onclick="closeModal()">No</button>
        </div>
    </div>
</div>