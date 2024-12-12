@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="{{ asset('css/adminorders.css') }}">
<script src="{{ asset('js/adminOrders.js') }}" defer></script>

<div class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <h1>{{ $user->username }}</h1>
        </div>
        @if ($user->username !== 'anonymous' . $user->id)
        <button class="button delete-button" onclick="openModal()" 
                style="background-color: #ff4d4d; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                Delete Account
        </button>
        @endif
        <div class="profile-content">
            <div class="profile-details">
                <h2>Profile Information</h2>
                <div class="detail-row"><strong>Name:</strong> {{ $user->name }}</div>
                <div class="detail-row"><strong>Username:</strong> {{ $user->username }}</div>
                <div class="detail-row"><strong>Email:</strong> {{ $user->email }}</div>
                @if ($user->username !== 'anonymous' . $user->id)
                <div class="detail-row">
                    <strong>Status:</strong> 
                    <span class="{{ $user->blocked ? 'blocked' : 'active' }}">
                        {{ $user->blocked ? 'Blocked' : 'Active' }}
                    </span>
                </div>
                @endif
            </div>

            @include('partials.ordersAdmin')

            <form action="{{ route('admin.user.change') }}" method="POST" class="admin-edit-profile">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <button type="submit" class="edit-button">Edit Profile</button>
            </form>

            
        </div>
    </div>
</div>

<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content" style="background: white; padding: 20px; border-radius: 8px; width: 300px; margin: 100px auto; text-align: center; position: relative;">
        <h3 style="margin-bottom: 20px; color: #ff4d4d;">Delete Account</h3>
        <p style="margin-bottom: 20px;">Are you sure you want to delete this user? This action is irreversible, and all the user data will be permanently removed.</p>
        <form action="{{ route('admin.user.delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="user_id" value="{{ $user->id }}">
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