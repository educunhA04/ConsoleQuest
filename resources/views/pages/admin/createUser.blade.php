@extends('layouts.admin')

@section('content')
    <div class="admin-create-form-container">
        <h1>Create New User</h1>
        
        <form action="{{ route('admin.storeUser') }}" method="POST" class="admin-create-form">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>

            <button type="submit" class="admin-submit-btn">Create User</button>
        </form>
    </div>
@endsection
