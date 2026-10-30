@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a> / Orders
</div>

<div class="page-header">
    <div class="page-title">
        <h1>Orders</h1>
        <p>Your orders and their line items.</p>
    </div>
    <a href="{{ route('orders.create') }}" class="btn-new">
        + New order
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Search Bar Area -->
<form class="search-bar" method="GET" action="{{ route('orders.index') }}" style="display: flex; gap: 10px; align-items: center; margin-bottom: 20px;">
    
    <!-- Search Input Group -->
    <div style="position: relative; display: flex; align-items: center; width: 350px;">
        <!-- မှန်ဘီလူး Icon -->
        <svg style="position: absolute; left: 12px; color: #9ca3af;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search orders by ID, status, or item name..." 
            style="width: 100%; padding: 10px 30px 10px 36px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; outline: none;"
        >

        <!-- Input ထဲက x ခလုတ် (စာသားရှိမှ ပေါ်မယ်) -->
        @if(request('search'))
            <a href="{{ route('orders.index') }}" style="position: absolute; right: 12px; color: #4f46e5; text-decoration: none; font-size: 16px; font-weight: bold;">&times;</a>
        @endif
    </div>

    <!-- Search ခလုတ် -->
    <button type="submit" class="btn-secondary" style="padding: 10px 16px;">Search</button>

    <!-- အပြင်က Clear ခလုတ် (စာသားရှိမှ ပေါ်မယ်) -->
    @if(request('search'))
        <a href="{{ route('orders.index') }}" class="btn-secondary" style="padding: 10px 16px; text-decoration: none;">Clear</a>
    @endif
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Order</th>
                <th>Status</th>
                <th>Items</th>
                <th>Total</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td style="font-weight: 500;">#{{ $order->id }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->items_count }}</td>
                    <td>${{ number_format($order->total, 2) }}</td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="{{ route('orders.show', $order) }}" class="btn-action">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            View
                        </a>
                        <a href="{{ route('orders.show', $order) }}" class="btn-action">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            Edit
                        </a>
                        <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this order?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #6b7280; padding: 40px 0;">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $orders->links() }}
</div>
@endsection