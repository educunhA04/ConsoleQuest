@extends('layouts.admin')

@section('content')
<div class="admin-dashboard">
    <h1>Welcome, {{ Auth::user()->name }}!</h1>

    @if (isset($users))
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
        <a href="{{ route('admin.createUser') }}" class="admin-button-link">New User</a>
    @elseif (isset($products))
        <h2>Products:</h2>
        
        <div class="product-list">
            @foreach ($products as $product)
            <div class="product-container">
            
            <a href="{{ route('admin.viewProduct', ['id' => $product->id]) }}" class="product-link">
                <img src="{{ asset('storage/' . $product->image) }}"alt="{{ $product->name }}" class="product-image">
                <div class="product-name">{{ $product->name }}</div>
            </a>


        </div>
            @endforeach
        </div>
        <a href="{{ route('admin.createProduct') }}" class="admin-button-link">New Product</a>
    @endif
</div>
@endsection
