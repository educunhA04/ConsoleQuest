
<head>
    <link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
</head>

<div class="orders-section">
    <h2>My Orders</h2>

    <div class="orders-container">
        <!-- Mock Order 1 -->
        <div class="order-block" onclick="openOrderDetails('1001', '2024-11-15', 'Shipped', '$49.99', ['Product A', 'Product B'])">
            <p><strong>Tracking ID:</strong> 1001</p>
            <p><strong>Date:</strong> 2024-11-15</p>
        </div>

        <!-- Mock Order 2 -->
        <div class="order-block" onclick="openOrderDetails('1002', '2024-11-10', 'Processing', '$89.50', ['Product C', 'Product D'])">
            <p><strong>Tracking ID:</strong> 1002</p>
            <p><strong>Date:</strong> 2024-11-10</p>
        </div>

        <!-- Mock Order 3 -->
        <div class="order-block" onclick="openOrderDetails('1003', '2024-11-05', 'Delivered', '$29.95', ['Product E', 'Product F'])">
            <p><strong>Tracking ID:</strong> 1003</p>
            <p><strong>Date:</strong> 2024-11-05</p>
        </div>

        <!-- Mock Order 4 -->
        <div class="order-block" onclick="openOrderDetails('1004', '2024-11-01', 'Shipped', '$74.00', ['Product G', 'Product H'])">
            <p><strong>Tracking ID:</strong> 1004</p>
            <p><strong>Date:</strong> 2024-11-01</p>
        </div>
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
    function openOrderDetails(trackingId, date, status, total, products) {
        document.getElementById('modalTrackingId').textContent = trackingId;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalStatus').textContent = status;
        document.getElementById('modalTotal').textContent = total;

        const productsList = document.getElementById('modalProducts');
        productsList.innerHTML = ''; // Clear previous list
        products.forEach(product => {
            const listItem = document.createElement('li');
            listItem.textContent = product;
            productsList.appendChild(listItem);
        });

        document.getElementById('orderModal').style.display = 'block';
    }

    function closeOrderDetails() {
        document.getElementById('orderModal').style.display = 'none';
    }
</script>