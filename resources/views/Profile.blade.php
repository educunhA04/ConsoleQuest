@extends('layouts.app')
@section('content')

<div class="profile-page">
    <div class="profile-container">
        <!-- Header Section -->
        <div class="profile-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h1 id="myprofile">My Profile</h1>
            <button class="button delete-button" onclick="openModal()" 
                style="background-color: #ff4d4d; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                Delete Account
            </button>
        </div>

        <!-- Main Content Section -->
        <div class="profile-content">
            <!-- Left Section -->
            <div class="left-section">
                <div class="profile-details">
                    <div class="profile-picture">
                        <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="Profile Picture">
                    </div>
                    <div class="profile-info">
                        <h2>Profile Information</h2>
                        <div class="detail-row"><strong>Name:</strong> {{ Auth::user()->name }}</div>
                        <div class="detail-row"><strong>Username:</strong> {{ Auth::user()->username }}</div>
                        <div class="detail-row"><strong>Email:</strong> {{ Auth::user()->email }}</div>
                    </div>
                </div>
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
            <button type="button" class="button cancel-button" onclick="closeModal()" 
                style="background-color: #ccc; color: black; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                Cancel
            </button>
        </form>
    </div>
</div>

<script>
    // Function to open the modal
    function openModal() {
        document.getElementById('deleteModal').style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>

@endsection
