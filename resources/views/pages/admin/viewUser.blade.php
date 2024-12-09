@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="{{ asset('css/adminorders.css') }}">
<script src="{{ asset('js/adminOrders.js') }}" defer></script>

<div class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <h1>{{ $user->username }}</h1>
        </div>
        <div class="profile-content">
            <div class="profile-details">
                <h2>Profile Information</h2>
                <div class="detail-row"><strong>Name:</strong> {{ $user->name }}</div>
                <div class="detail-row"><strong>Username:</strong> {{ $user->username }}</div>
                <div class="detail-row"><strong>Email:</strong> {{ $user->email }}</div>
            </div>

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
                                <button class="button-link view-details-btn" onclick="openOrderDetailsFromElementAdmin(this.parentElement)">
                                    View Details
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <form action="{{ route('admin.user.change') }}" method="POST" class="admin-edit-profile">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button type="submit" class="edit-button">Edit Profile</button>
        </form>
    </div>
</div>

<div id="orderModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeOrderDetailsAdmin()">&times;</span>
        <h2>Order Details</h2>
        <p><strong>Tracking ID:</strong> <span id="modalTrackingId"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Products:</strong></p>
        <ul id="modalProducts" class="product-list"></ul> <!-- Products list -->
    </div>
</div>


@endsection
