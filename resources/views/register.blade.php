@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-card">
    <h1>Create an account</h1>
    <p class="subtitle">Fill in your details to get started.</p>

    @if ($errors->any())
        <div class="error" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf
        <div class="field">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <button type="submit" class="btn-primary">Register</button>
    </form>

    <p class="footer-text">
        Already have an account? <a href="{{ route('login') }}">Log in</a>
    </p>
</div>
@endsection