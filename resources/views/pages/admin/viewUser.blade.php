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

            @include('partials/ordersAdmin')


        <form action="{{ route('admin.user.change') }}" method="POST" class="admin-edit-profile">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <button type="submit" class="edit-button">Edit Profile</button>
        </form>
    </div>
</div>




@endsection
