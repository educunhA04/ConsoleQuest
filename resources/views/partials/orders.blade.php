<head>
    <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
</head>

<div class="orders-section">
    <h2>My Orders</h2>

    <div class="orders-container">
        @foreach ($orders->sortByDesc('tracking_number') as $order)
        <!-- Each order block -->
        <div class="order-block" 
            data-tracking="{{ $order->tracking_number }}" 
            data-date="{{ $order->buy_date }}" 
            data-status="{{ ucfirst($order->status) }}" 
            data-total="{{ $order->products->sum(function($item) { return $item->quantity * $item->product->price; }) }}" 
            data-products='@json($order->products->map(function($item) { return ['name' => $item->product->name, 'quantity' => $item->quantity, 'price' => $item->product->price]; }))' 
            data-images='@json($order->products->map(function($item) { return asset('storage/' . $item->product->image); }))'
            onclick="openOrderDetailsFromElement(this)">
            <p><strong>Tracking ID:</strong> {{ $order->tracking_number }}</p>
            <p><strong>Date:</strong> {{ $order->buy_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>
        @endforeach
    </div>

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
    function openOrderDetailsFromElement(element) {
        // Lê os atributos data-* do elemento clicado
        const trackingId = element.getAttribute('data-tracking');
        const date = element.getAttribute('data-date');
        const status = element.getAttribute('data-status');
        const total = parseFloat(element.getAttribute('data-total')).toFixed(2);
        const products = JSON.parse(element.getAttribute('data-products'));
        const images = JSON.parse(element.getAttribute('data-images'));

        // Chama a função para exibir o modal com os detalhes
        openOrderDetails(trackingId, date, status, total, products, images);
    }

    function openOrderDetails(trackingId, date, status, total, products, images) {
        // Update modal content dynamically
        document.getElementById('modalTrackingId').textContent = trackingId;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalStatus').textContent = status;
        document.getElementById('modalTotal').textContent = `€${parseFloat(total).toFixed(2)}`;

        // Clear and update the products list
        const productsList = document.getElementById('modalProducts');
        productsList.innerHTML = ''; // Clear previous list
        products.forEach((product, index) => {
            const listItem = document.createElement('li');
            const img = document.createElement('img');
            img.src = images[index];
            img.alt = product.name;
            img.style.width = '50px';
            img.style.height = '50px';
            img.style.marginRight = '10px';
            listItem.appendChild(img);

            // Calculate total value for this product
            const totalValue = parseFloat(product.quantity * product.price).toFixed(2);

            // Add product details with total value
            listItem.appendChild(document.createTextNode(`${product.name} - Quantity: ${product.quantity} - Price: €${parseFloat(product.price).toFixed(2)} - Total: €${totalValue}`));
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