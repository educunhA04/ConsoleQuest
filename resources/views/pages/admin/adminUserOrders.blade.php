<!--@extends('layouts.admin')
@include('pages.admin.ordersList', ['user' => $user])


@section('content')
<div class="user-orders">
    <h1>{{ $user->name }}'s Orders</h1>

     Include the Orders List 
    @include('pages.admin.ordersList', ['user' => $user])
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/adminorders.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/adminOrders.js') }}"></script>
@endsection
-->