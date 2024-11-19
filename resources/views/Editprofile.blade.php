@extends('layouts.app')
@section('content')

<div class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <h1>Edit Profile</h1>
        </div>
        <div class="profile-content">
            <!-- Edit Profile Form -->
            <form action="{{ url('/updateprofile') }}" method="POST" class="profile-form">
                @csrf
 
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                </div>
                

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ Auth::user()->username }}" required>
                </div>


                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
                </div>


                <div class="form-group">
                    <label for="password">New Password (leave blank to keep current password)</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>


                <div class="form-group">
                    <button type="submit" class="button save-button">Save Changes</button>
                    <a class="button cancel-button" href="{{ url('/profile') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection