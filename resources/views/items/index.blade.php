@extends('layouts.app')

@section('title', 'Items')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a> / Items
</div>

<div class="page-header">
    <div class="page-title">
        <h1>Items</h1>
        <p>Manage catalog items used in orders.</p>
    </div>
    <a href="{{ route('items.create') }}" class="btn-new">
        + New item
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<!-- Search Bar Area -->
<form class="search-bar" method="GET" action="{{ route('items.index') }}" style="display: flex; gap: 10px; align-items: center;">
    
    <!-- Search Input Group -->
    <div style="position: relative; display: flex; align-items: center; width: 350px;">
        <!-- မှန်ဘီလူး Icon -->
        <svg style="position: absolute; left: 12px; color: #9ca3af;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        
        <input 
            type="text" 
            name="search" 
            id="searchInput"
            value="{{ request('search') }}" 
            placeholder="Search items by name or description..." 
            style="width: 100%; padding: 10px 10px 10px 36px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; outline: none;"
        >

        <!-- Input ထဲက x ခလုတ် (စာသားရှိမှ ပေါ်မယ်) -->
        @if(request('search'))
            <a href="{{ route('items.index') }}" style="position: absolute; right: 12px; color: #4f46e5; text-decoration: none; font-size: 16px; font-weight: bold;">&times;</a>
        @endif
    </div>

    <!-- Search ခလုတ် -->
    <button type="submit" class="btn-secondary" style="padding: 10px 16px;">Search</button>

    <!-- အပြင်က Clear ခလုတ် (စာသားရှိမှ ပေါ်မယ်) -->
    @if(request('search'))
        <a href="{{ route('items.index') }}" class="btn-secondary" style="padding: 10px 16px; text-decoration: none;">Clear</a>
    @endif
</form>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Orders</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td style="font-weight: 500;">{{ $item->name }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->orders_count }}</td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="{{ route('items.show', $item) }}" class="btn-action">View</a>
                        <a href="{{ route('items.edit', $item) }}" class="btn-action">Edit</a>
                        <form action="{{ route('items.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #6b7280; padding: 40px 0;">No items yet. Create your first item.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $items->links() }}
</div>
@endsection