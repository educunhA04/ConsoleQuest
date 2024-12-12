<div class="orders-section">
                <h2>{{ $user->username }}'s Orders</h2>
                <div class="orders-container">
                    @if ($user->orders->isEmpty())
                        <p>This user doesn't have any orders to display yet.</p>
                    @else
                        @foreach ($user->orders as $order)
                            @php
                                $orderData = [
                                    'trackingId' => $order->tracking_number,
                                    'date' => $order->buy_date->format('Y-m-d'),
                                    'status' => ucfirst($order->status),
                                    'total' => number_format($order->orderProducts->sum(fn($item) => $item->quantity * $item->product->price), 2),
                                    'products' => $order->orderProducts->map(function ($item) {
                                        return [
                                            'name' => $item->product->name,
                                            'quantity' => $item->quantity,
                                            'price' => $item->product->price,
                                            'image' => $item->product->image ? asset('storage/' . $item->product->image) : null,
                                        ];
                                    })->toArray(),
                                ];
                            @endphp
                            <div class="order-block" 
                                data-tracking="{{ $order->tracking_number }}" 
                                data-date="{{ $order->buy_date->format('Y-m-d') }}" 
                                data-status="{{ ucfirst($order->status) }}" 
                                data-total="{{ $orderData['total'] }}" 
                                data-products='@json($orderData['products'])'>
                                <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                               
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
</div>
<div id="orderModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeOrderDetailsAdmin()">&times;</span>
        <h2>Order Details</h2>
        <p><strong>Tracking ID:</strong> <span id="modalTrackingId"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <form action="{{ route('admin.orders.updateStatus', ['id' => $order->id]) }}" method="POST" style="display: inline;">
                                  @csrf
            @method('PUT') <!-- Use PUT or PATCH for updates -->
            <select name="status" class="status-dropdown">
            <option selected value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="hidden" id = "user_id" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="update-status-btn">Update Status</button>
        </form>
        <p><strong>Products:</strong></p>
        <ul id="modalProducts" class="product-list"></ul> <!-- Products list -->
    </div>
</div>