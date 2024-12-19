@extends('layouts.app')

@section('content')

<div class="edit-profile-page">
    <div class="edit-profile-container">
        <div class="edit-profile-header">
            <h1>Edit Profile</h1>
        </div>

        <div class="edit-profile-content">
            <!-- Edit Profile Form -->
            <form action="{{ url('/updateprofile') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                @csrf

                <div class="form-columns">
                    <!-- First Column -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-control" value="{{ old('username', Auth::user()->username) }}" required>
                            @error('username')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-container">
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                                <span class="tooltip-icon" data-tooltip="Enter a valid email address (e.g., example@example.com)">?</span>
                            </div>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="shipping_address">Shipping Address</label>
                            <textarea id="shipping_address" name="shipping_address" class="form-control" rows="1">{{ old('shipping_address', Auth::user()->shipping_address) }}</textarea>
                            @error('shipping_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Second Column -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <div class="input-container">
                                <input type="password" id="password" name="password" class="form-control">
                                <span class="tooltip-icon" data-tooltip="Password must include uppercase, lowercase, and a number.">?</span>
                            </div>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <div class="input-container">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                                <span class="tooltip-icon" data-tooltip="Ensure this matches your password.">?</span>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="profile_picture">Profile Picture</label>
                            <div class="input-container">
                                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            </div>
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="edit-profile-buttons">
                    <button type="submit" class="button save-button">Save Changes</button>
                    <a class="button cancel-button" href="{{ url('/profile') }}">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Errors Section -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

@endsection
