@extends('layouts.admin')

@section('content')
    <div class="admin-create-form-container">
        <h1>Create New User</h1>
        
        <form action="{{ route('admin.storeUser') }}" method="POST" class="admin-create-form">
            @csrf
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>


            <button type="submit" class="admin-submit-btn">Create User</button>
        </form>
    </div>
@endsection
