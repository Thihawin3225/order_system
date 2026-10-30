@extends('layouts.auth')

@section('title', 'Log in')

@section('content')
<div class="auth-card">
    <h1>Log in</h1>
    <p class="subtitle">Welcome back. Enter your credentials to continue.</p>

    @if ($errors->any())
        <div class="error" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>

        <button type="submit" class="btn-primary">Log in</button>
    </form>

    <p class="footer-text">
        Don't have an account? <a href="{{ route('register') }}">Register</a>
    </p>
</div>
@endsection