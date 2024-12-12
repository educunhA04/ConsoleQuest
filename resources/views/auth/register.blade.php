@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }} " enctype="multipart/form-data" id = "register-form">
    {{ csrf_field() }}

    <label for="username">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif
    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <div class="input-container">
      <input id="email" type="email" name="email" value="{{ old('email') }}" required>
      <span class="tooltip-icon" data-tooltip="Enter a valid email address (e.g., example@example.com)">?</span>
    </div>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

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

    <label for="password-confirm">Confirm Password</label>
    <div class="input-container">
      <input id="password-confirm" type="password" name="password_confirmation" required>
      <span class="tooltip-icon" data-tooltip="Ensure your password confirmation matches the password.">?</span>
    </div>
    
    <label for="image">Profile Picture (Optional)</label>
    <input id="image" type="file" name="image" accept="image/*">
    @if ($errors->has('image'))
      <span class="error">
          {{ $errors->first('image') }}
      </span>
    @endif

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection