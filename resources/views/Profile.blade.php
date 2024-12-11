@extends('layouts.app')
@section('content')

<div class="profile-page">
    <div class="profile-container">
        <!-- Header Section -->
        <div class="profile-header">
            <h1 id="myprofile">My Profile</h1>
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
                    <h2>Notifications</h2>
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

@endsection
