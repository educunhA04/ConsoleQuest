@extends('layouts.app')

@section('content')
<div class="password-recovery-container">
    <h2>Recover Your Password</h2>
    <form method="POST" action="{{ route('password.email') }}" id="password-recovery-form">
        @csrf

        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

        @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
        @endif

        <button type="submit">Send Password Reset Link</button>

        <a href="{{ route('login') }}">Login here</a>
    </form>
</div>
@endsection
