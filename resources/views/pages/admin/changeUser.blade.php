@extends('layouts.admin')

@section('content')
<div class="profile-page">
    <div class="profile-container">
        <div class="profile-header">
            <h1>Edit Profile</h1>
        </div>
        <div class="profile-content">
            <form action="{{ route('admin.updateProfile') }}" method="POST" class="admin-profile-form">
                @csrf
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                

                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ $user->username }}" required>


                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>


                    <label for="password">New Password (leave blank to keep current password)</label>
                    <input type="password" id="password" name="password" class="form-control">

                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">

                <input type="hidden" id = "user_id" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="button save-button">Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection
