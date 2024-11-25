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

            <!-- Include Orders Section -->
            @include('partials/orders')
        </div>
        <div class= "profile-buttons">
        <a class="button" href="{{ url('/logout') }}"> Logout </a>
        <a class="button" href="{{ url('/editprofile') }}"> Edit Profile </a>
        </div>
    </div>
    
</div>

@endsection
