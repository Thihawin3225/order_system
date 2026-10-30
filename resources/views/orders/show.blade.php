@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a> / 
    <a href="{{ route('orders.index') }}">Orders</a> / 
    Order #{{ $order->id }}
</div>

<div class="page-header" style="align-items: flex-start; margin-bottom: 20px;">
    <div class="page-title">
        <h1>Order #{{ $order->id }}</h1>
        <p style="font-size: 14px; margin-top: 8px; color: #6b7280;">
            Status: {{ ucfirst($order->status) }} &middot; Total ${{ number_format($order->total, 2) }}
        </p>
    </div>
    
    <div style="display: flex; gap: 10px;">
        <!-- Edit Order -->
        <a href="{{ route('orders.edit', $order) }}" class="btn-action" style="padding: 8px 16px;">Edit</a>
        
        <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action btn-delete" style="padding: 8px 16px;">Delete</button>
        </form>
    </div>
</div>

<h2 style="font-size: 20px; font-weight: 600; margin: 32px 0 16px 0;">Line items</h2>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Unit price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->pivot->quantity }}</td>
                    <td>${{ number_format($item->pivot->unit_price, 2) }}</td>
                    <td>${{ number_format($item->pivot->unit_price * $item->pivot->quantity, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #6b7280; padding: 24px 0;">
                        No items in this order.
                    </td>
                </tr>
            @endforelse
        </tbody>
        
        <!-- ဒီနေရာမှာ Main Total ကို ထည့်ပေးထားပါတယ် -->
        @if($order->items->count() > 0)
        <tfoot>
            <tr style="background: #f9fafb; border-top: 2px solid #e5e7eb;">
                <!-- ပထမ ၃ ကော်လံကို ပေါင်းပြီး Total Cost လို့ ပြမယ် -->
                <td colspan="3" style="text-align: right; font-weight: 600; padding: 16px;">Total Cost</td>
                <!-- နောက်ဆုံး ကော်လံမှာ Main Total ကို ပြမယ် -->
                <td style="font-weight: 700; padding: 16px;">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
        @endif

    </table>
</div>
@endsection