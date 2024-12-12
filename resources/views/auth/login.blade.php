@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}" id="login-form">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="login">E-mail or Username</label>
        <div class="input-container">
            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus>
            <span class="tooltip-icon" data-tooltip="Enter your registered email or username.">?</span>
        </div>
        @if ($errors->has('login'))
            <span class="error">
                {{ $errors->first('login') }}
            </span>
        @endif
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <div class="input-container">
            <input id="password" type="password" name="password" required>
            <span class="tooltip-icon" data-tooltip="Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, and a number.">?</span>
        </div>
        @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
        @endif
    </div>



    <div id ="rememberec" class="form-group">
        <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
        </label>
        <a href="{{ route('password.request') }}" class="recover-password">Recover Password</a>
    </div>


    <button type="submit" class="btn btn-primary">
        Login
    </button>
    <a class="btn btn-outline-secondary" href="{{ route('register') }}">Register</a>

    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
@endsection