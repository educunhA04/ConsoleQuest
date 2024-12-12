<div class="orders-section">
    <h2>My Orders</h2>

    <div class="orders-container">
        @if ($orders->isEmpty())
            <p>You don't have any orders to display yet</p>
        @else
            @foreach ($orders->sortByDesc('tracking_number') as $order)
                <!-- Each order block -->
                <div 
                    class="order-block" 
                    data-tracking="{{ $order->tracking_number }}" 
                    data-date="{{ $order->buy_date->format('Y-m-d')}}" 
                    data-status="{{ ucfirst($order->status) }}" 
                    data-total="{{ $order->products->sum(function($item) { return $item->quantity * $item->product->price; }) }}" 
                    data-products='@json($order->products->map(function($item) { return ['name' => $item->product->name, 'quantity' => $item->quantity, 'price' => $item->product->price]; }))' 
                    data-images='@json($order->products->map(function($item) { return asset('storage/' . $item->product->image); }))'
                    data-product-page='@json($order->products->map(function($item) { 
                        return ['url' => route("product.show", $item->product->id)]; 
                    }))' 
                    onclick="openOrderDetailsFromElement(this)">
                    <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
                    <p><strong>Date:</strong> {{ $order->buy_date->format('Y-m-d') }}</p>
                    <p><strong>Status:</strong> <span id="status-{{ $order->id }}">{{ ucfirst($order->status) }}</span></p>
                    @if ($order->status === 'processing')
                    <form action="{{ route('orders.cancel', ['orderId' => $order->id]) }}" method="POST" style="display:inline;">
                        @csrf

                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <button type="submit" class="cancel-order-btn" onclick="event.stopPropagation();">
                            Cancel Order
                        </button>

                    </form>

                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeOrderDetails()">&times;</span>
        <h2>Order Details</h2>
        <p><strong>Tracking ID:</strong> <span id="modalTrackingId"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Total:</strong> <span id="modalTotal"></span></p>
        <p><strong>Products:</strong></p>
        <ul id="modalProducts" class="product-list"></ul>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Are you sure you want to cancel this order?</h3>
        <div>
            <button class="confirm-btn" id="confirmCancel">Yes</button>
            <button class="cancel-btn" onclick="closeModal()">No</button>
        </div>
    </div>
</div>
