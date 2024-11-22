@extends('layouts.app')
@section('content')

<div class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <h1>My Profile</h1>
        </div>
        <div class="profile-content">
            <!-- User Information Section -->
            <div class="profile-details">
                <h2>Profile Information</h2>
                <div class="detail-row"><strong>Name:</strong> {{ Auth::user()->name }}</div>
                <div class="detail-row"><strong>Username:</strong> {{ Auth::user()->username }}</div>
                <div class="detail-row"><strong>Email:</strong> {{ Auth::user()->email }}</div>
            </div>

            <!-- My Orders Section -->
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
        </div>
    </div>
</div>

<style>
    /* Orders container styles */
    .orders-container {
        max-height: 300px; /* Display up to 3 orders (adjust height as needed) */
        overflow-y: auto; /* Enable vertical scrolling */
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .order-block {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fff;
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .order-block:hover {
        background-color: #f1f1f1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Modal styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }
</style>

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

@endsection
