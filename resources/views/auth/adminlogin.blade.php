@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('admin.authenticate') }}">
    {{ csrf_field() }}

    <!-- Campo de e-mail -->
    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
    @endif

    <!-- Campo de senha -->
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <!-- Checkbox "Remember Me" -->
    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <!-- Botão de Login -->
    <button type="submit">
        Login
    </button>

    <!-- Mensagens de Sucesso -->
    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
@endsection
