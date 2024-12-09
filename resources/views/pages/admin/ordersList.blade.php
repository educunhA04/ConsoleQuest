<div class="orders-section">
    <h2>{{ $user->username ?? 'User' }}'s Orders</h2>

    <div class="orders-container">
        @if ($user->orders->isEmpty())
            <p>No orders to display yet.</p>
        @else
            @foreach ($user->orders as $order)
                @php
                    $orderData = [
                        'trackingId' => $order->tracking_number,
                        'date' => $order->buy_date->format('Y-m-d'),
                        'status' => ucfirst($order->status),
                        'total' => number_format($order->products->sum(fn($p) => ($p->pivot->quantity ?? 0) * $p->price), 2),
                        'products' => $order->products->map(fn($p) => [
                            'name' => $p->name,
                            'quantity' => $p->pivot->quantity ?? 0,
                            'price' => $p->price,
                            'image' => $p->image ? asset('storage/' . $p->image) : null,
                        ]),
                    ];
                @endphp
                <div class="order-block" data-order='@json($orderData)'>
                    <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    <button 
                        class="button-link view-details-btn" 
                        data-order='@json($orderData)'>
                        View Details
                    </button>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Modal for Order Details -->
    <div id="orderModal" class="modal" style="display: none;">
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
</div>
