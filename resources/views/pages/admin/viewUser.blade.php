@extends('layouts.admin')
@section('content')

<div class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <h1>{{$user->username}}</h1>
        </div>
        <div class="profile-content">
            <!-- User Information Section -->
            <div class="profile-details">
                <h2>Profile Information</h2>
                <div class="detail-row"><strong>Name:</strong> {{ $user->name }}</div>
                <div class="detail-row"><strong>Username:</strong> {{ $user->username }}</div>
                <div class="detail-row"><strong>Email:</strong> {{ $user->email }}</div>
            </div>

            <!-- My Orders Section -->
            <div class="orders-section">
                <h2> {{$user->username}}'s Orders</h2>
                <div class="orders-placeholder">
                    <p>No orders to display yet.</p>
                </div>
            </div>
        </div>
        <form action="{{ route('admin.changeUser') }}" method="POST" class = "admin-edit-profile" >
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button type="submit" class="edit-button">Edit Profile</button>
        </form>
    </div>
    
</div>

@endsection