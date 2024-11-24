@extends('layouts.admin')

@section('content')
<div class="admin-dashboard">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>

        <h2>Users:</h2>
        <div class="user-list">
            @foreach ($users as $user)
                <div class="user-card">
                    <h3>{{ $user->username }}</h3>
                    <p>Email: {{ $user->email }}</p>
                    <p>Name: {{ $user->name }}</p>
                    <a href="{{ route('admin.viewUser', $user->username) }}">View Profile</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
