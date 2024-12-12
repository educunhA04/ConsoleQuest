@extends('layouts.app')

@section('content')
<div class="password-set-container">
    <h2>Set Your New Password</h2>
    <form method="POST" action="{{ route('password.update') }}" id="password-set-form">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

        @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
        @endif

        <label for="password">New Password</label>
        <input id="password" type="password" name="password" required>

        @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
        @endif

        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>

        @if ($errors->has('password_confirmation'))
            <span class="error">
                {{ $errors->first('password_confirmation') }}
            </span>
        @endif

        <button type="submit">Set New Password</button>

        <a href="{{ route('login') }}">Login here</a>
    </form>
</div>
@endsection
