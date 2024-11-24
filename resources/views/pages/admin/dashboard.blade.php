@extends('layouts.admin')

@section('content')
<div class="admin-dashboard">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>

        <h2>Users:</h2>
        <div class="user-list">
            @foreach ($users as $user)
                <div class="user-card">
                    <h3>{{ $user->name }}</h3>
                    <p>Email: {{ $user->email }}</p>
                    <a href="{{ route('admin.viewUser', $user->id) }}">View Profile</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
