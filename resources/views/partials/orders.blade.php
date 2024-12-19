<div class="orders-section">
    <h2>My Orders</h2>

    <div class="orders-container">
        @if ($orders->isEmpty())
            <p>You don't have any orders to display yet</p>
        @else
            @foreach ($orders->sortByDesc('tracking_number') as $order)
                <div 
                    class="order-block" 
                    data-tracking="{{ $order->tracking_number }}" 
                    data-date="{{ $order->buy_date->format('Y-m-d')}}" 
                    data-status="{{ ucfirst($order->status) }}" 
                    data-total="{{ $order->products ? $order->products->sum(function($item) { 
                        return $item->quantity * $item->product->price; 
                    }) : 0 }}" 
                    data-products='@json($order->products ? $order->products->map(function($item) { 
                        return ['name' => $item->product->name, 'quantity' => $item->quantity, 'price' => $item->product->price]; 
                    }) : [])' 
                    data-images='@json($order->products->map(function($item) { 
                        return asset("storage/" . $item->product->images->first()->url); 
                    }))'

                    data-product-page='@json($order->products ? $order->products->map(function($item) { 
                        return ['url' => route("product.show", $item->product->id)]; 
                    }) : [])' 
                    onclick="openOrderDetailsFromElement(this)">
                    <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
                    <p><strong>Date:</strong> {{ $order->buy_date->format('Y-m-d') }}</p>
                    <p><strong>Status:</strong> <span id="status-{{ $order->id }}">{{ ucfirst($order->status) }}</span></p>
                    <p><strong>Shipping Address:</strong></p>
                    <ul>
                        <li><strong>Address:</strong> {{ $order->address }}</li>
                        <li><strong>Postal Code:</strong> {{ $order->postal_code }}</li>
                        <li><strong>Location:</strong> {{ $order->location }}</li>
                        <li><strong>Country:</strong> {{ $order->country }}</li>
                    </ul>
                    @if ($order->status === 'processing')
                    <button class="button cancel-order-btn" onclick="openCancelOrderModal(event, {{ $order->id }})" 
                        style="background-color: #ff4d4d; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                        Cancel Order
                    </button>
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

<!-- Cancel Order Modal -->
<div id="cancelOrderModal" class="modal" style="display: none;">
    <div class="modal-content" style="background: white; padding: 20px; border-radius: 8px; width: 300px; margin: 100px auto; text-align: center; position: relative;">
        <h3 style="margin-bottom: 20px; color: #ff4d4d;">Cancel Order</h3>
        <p style="margin-bottom: 20px;">Are you sure you want to cancel this order? This action is irreversible.</p>
        <form id="cancelOrderForm" method="POST">
            @csrf
            @method('POST')
            <button type="submit" class="button" style="background-color: #ff4d4d; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">Confirm</button>
            <button type="button" class="button cancel-button" onclick="closeCancelOrderModal()" 
                style="background-color: #ccc; color: black; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                Cancel
            </button>
        </form>
    </div>
</div>

