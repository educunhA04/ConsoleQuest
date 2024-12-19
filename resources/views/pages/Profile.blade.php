@extends('layouts.app')
@section('content')

<div class="profile-page">
    <div class="profile-container">
        <!-- Header Section -->
        <div class="profile-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 id="myprofile">My Profile</h1>
            <button class="button delete-button" onclick="openDeleteModal()" 
                style="background-color: #ff4d4d; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                Delete Account
            </button>
        </div>

        <!-- Main Content Section -->
        <div class="profile-content" style="display: flex; justify-content: space-between;">
            <!-- Left Section -->
            <div class="left-section" style="flex: 1;">
                <div class="profile-details" style="display: flex; justify-content: space-between; align-items: center;">
                    
                    <div class="profile-info" style="flex: 1;">
                        <h2>Profile Information</h2>
                        <div class="detail-row"><strong>Name:</strong> {{ Auth::user()->name }}</div>
                        <div class="detail-row"><strong>Username:</strong> {{ Auth::user()->username }}</div>
                        <div class="detail-row"><strong>Email:</strong> {{ Auth::user()->email }}</div>
                        @php
                            $address = Auth::user()->shippingAddress; // Assuming a one-to-one relationship
                        @endphp

                        @if ($address)
                            <div class="detail-row">
                                <strong>Address:</strong> {{ $address->address }}<br>
                                <strong>Postal Code:</strong> {{ $address->postal_code }}<br>
                                <strong>Location:</strong> {{ $address->location }}<br>
                                <strong>Country:</strong> {{ $address->country }}
                            </div>
                        @else
                            <p>No shipping address available.</p>
                        @endif
                 
                     </div>
                    <div class="profile-picture" style="margin-left: 20px;">
                        <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="Profile Picture">
                    </div>
                </div>
                <h2>Your Notifications</h2>

                <div class="notifications-section">
                    @include('partials/notifications', ['notifications' => $notifications])
                </div>
            </div>

            <!-- Right Section -->
            @include('partials/orders')
        </div>

        <!-- Buttons Section -->
        <div class="profile-buttons">
            <a class="button" href="{{ url('/logout') }}">Logout</a>
            <a class="button" href="{{ url('/editprofile') }}">Edit Profile</a>
        </div>
    </div>
</div>

<!-- Modal Popup -->
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content" style="background: white; padding: 20px; border-radius: 8px; width: 300px; margin: 100px auto; text-align: center; position: relative;">
        <h3 style="margin-bottom: 20px; color: #ff4d4d;">Delete Account</h3>
        <p style="margin-bottom: 20px;">Are you sure you want to delete your account? This action is irreversible, and all your data will be permanently removed.</p>
        <form action="{{ route('deleteAccount') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="button" style="background-color: #ff4d4d; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">Confirm</button>
            <button type="button" class="button cancel-button" onclick="closeDeleteModal()" 
                style="background-color: #ccc; color: black; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                Cancel
            </button>
        </form>
    </div>
</div>


<script>
    // Function to open the delete account modal
    function openDeleteModal() {
        document.getElementById('deleteModal').style.display = 'block';
    }

    // Function to close the delete account modal
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    
</script>

@endsection
