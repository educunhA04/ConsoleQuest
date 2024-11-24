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
                    <form action="{{ route('admin.viewUser') }}" method="POST" >
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button type="submit" class="button-link">View Profile</button>
                    </form>


                </div>
            @endforeach
        </div>
    </div>
@endsection
