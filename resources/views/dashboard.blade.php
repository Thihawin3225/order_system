@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="breadcrumb">
    Dashboard
</div>
<div class="page-header">
    <div class="page-title">
        <h1>Welcome, {{ $user->name }}!</h1>
        <p>Your account information</p>
    </div>
</div>

<div class="dash-card">
    <div class="dash-info">
        <div class="dash-label">Full Name</div>
        <div class="dash-value">{{ $user->name }}</div>
    </div>

    <div class="dash-info">
        <div class="dash-label">Email Address</div>
        <div class="dash-value">{{ $user->email }}</div>
    </div>

    <div class="dash-info">
        <div class="dash-label">User ID</div>
        <div class="dash-value">{{ $user->id }}</div>
    </div>
</div>
@endsection