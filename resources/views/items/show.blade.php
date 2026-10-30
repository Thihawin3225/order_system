@extends('layouts.app')

@section('title', $item->name)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a> / 
    <a href="{{ route('items.index') }}">Items</a> / 
    {{ $item->name }}
</div>

<div class="page-header" style="align-items: flex-start; margin-bottom: 20px;">
    <div class="page-title">
        <h1>{{ $item->name }}</h1>
        <p style="font-size: 16px; margin-top: 4px;">${{ number_format($item->price, 2) }}</p>
        @if($item->description)
            <p style="margin-top: 12px; color: #374151; font-size: 14px;">{{ $item->description }}</p>
        @endif
    </div>
    
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('items.edit', $item) }}" class="btn-action" style="padding: 8px 16px;">Edit</a>
        
        <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action btn-delete" style="padding: 8px 16px;">Delete</button>
        </form>
    </div>
</div>

<h2 style="font-size: 20px; font-weight: 600; margin: 32px 0 16px 0;">Related orders</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Qty</th>
                <th>Unit price</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'Unknown' }}</td>
                    <td>{{ $order->pivot->quantity }}</td>
                    <td>${{ number_format($order->pivot->unit_price, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #6b7280; padding: 24px 0;">
                        No orders use this item yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection