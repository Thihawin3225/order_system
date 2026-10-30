@extends('layouts.app')

@section('title', 'Edit Order #' . $order->id)

@section('content')
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a> / 
    <a href="{{ route('orders.index') }}">Orders</a> / 
    <a href="{{ route('orders.show', $order) }}">Order #{{ $order->id }}</a> / 
    Edit
</div>

<div class="page-header">
    <div class="page-title">
        <h1>Edit order #{{ $order->id }}</h1>
        <p>Update status and line items.</p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-error">
        <ul style="margin:0; padding-left:20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('orders.update', $order) }}" method="POST" id="order-form">
    @csrf
    @method('PUT')
    
    <div class="form-card" style="max-width: 800px;">
        
        <div class="form-field">
            <label for="status">Status</label>
            <select id="status" name="status" required style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px; background:white;">
                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Completed" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                <option value="Completed" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 32px; margin-bottom: 16px;">
            <label style="font-size: 14px; font-weight: 500;">Items</label>
            <button type="button" id="add-item-btn" class="btn-secondary" style="padding: 6px 12px; font-size: 13px;">Add Item</button>
        </div>

        <div id="items-container">
            <!-- လက်ရှိ Order ထဲမှာ ပါဝင်နေတဲ့ Items တွေကို Loop ပတ်ပြမယ် -->
            @foreach($order->items as $index => $orderItem)
                <div class="item-row" style="display: flex; gap: 10px; margin-bottom: 12px; align-items: center;">
                    <select name="items[{{ $index }}][item_id]" class="item-select" required style="flex: 1; padding:10px; border:1px solid #d1d5db; border-radius:6px; background:white;">
                        <option value="">Select item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ $orderItem->id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} (${{ number_format($item->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="items[{{ $index }}][quantity]" value="{{ $orderItem->pivot->quantity }}" min="1" required style="width: 100px; padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                    <button type="button" class="btn-secondary remove-btn" style="padding: 10px 14px; border-color:#fca5a5; color:#dc2626;">Remove</button>
                </div>
            @endforeach
        </div>

        <!-- Box မရှိရင် ပြမယ့် စာသား -->
        <div id="no-items-msg" style="color: #6b7280; font-size: 14px; padding: 10px 0; display: {{ $order->items->count() > 0 ? 'none' : 'block' }};">
            Click Add Item to include products in this order.
        </div>

        <div class="form-actions" style="margin-top: 32px;">
            <button type="submit" class="btn-new">Update order</button>
            <a href="{{ route('orders.show', $order) }}" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // လက်ရှိ Box အရေအတွက်ကနေ စမယ်
        let itemIndex = {{ $order->items->count() }}; 
        const container = document.getElementById('items-container');
        const addBtn = document.getElementById('add-item-btn');
        const noItemsMsg = document.getElementById('no-items-msg');
        
        // Dropdown ထဲက Option တွေကို မှတ်ထားမယ် (Template အနေနဲ့)
        const tempSelect = document.createElement('select');
        tempSelect.innerHTML = `
            <option value="">Select item</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }} (${{ number_format($item->price, 2) }})</option>
            @endforeach
        `;
        const originalOptions = tempSelect.innerHTML;

        function toggleNoItemsMsg() {
            if (container.children.length === 0) {
                noItemsMsg.style.display = 'block';
            } else {
                noItemsMsg.style.display = 'none';
            }
        }

        // ရွေးပြီးသား Item တွေကို တခြား Box တွေမှာ ဖျောက်မယ်
        function updateSelectOptions() {
            const selects = document.querySelectorAll('.item-select');
            const selectedValues = Array.from(selects)
                .map(select => select.value)
                .filter(val => val !== "");

            selects.forEach(select => {
                const currentValue = select.value;
                select.innerHTML = originalOptions;
                
                Array.from(select.options).forEach(option => {
                    if (option.value !== "" && selectedValues.includes(option.value) && option.value !== currentValue) {
                        option.style.display = 'none';
                    }
                });
                
                select.value = currentValue; // လက်ရှိ Value ကို ပြန်သတ်မှတ်
            });
        }

        // Box အသစ် ထပ်ထည့်တဲ့အခါ
        addBtn.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'item-row';
            newRow.style = 'display: flex; gap: 10px; margin-bottom: 12px; align-items: center;';
            
            newRow.innerHTML = `
                <select name="items[${itemIndex}][item_id]" class="item-select" required style="flex: 1; padding:10px; border:1px solid #d1d5db; border-radius:6px; background:white;">
                    ${originalOptions}
                </select>
                <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" required style="width: 100px; padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                <button type="button" class="btn-secondary remove-btn" style="padding: 10px 14px; border-color:#fca5a5; color:#dc2626;">Remove</button>
            `;
            
            container.appendChild(newRow);
            itemIndex++;
            updateSelectOptions();
            toggleNoItemsMsg();
        });

        // Remove နှိပ်ရင် Box ဖျက်မယ်
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-btn')) {
                e.target.closest('.item-row').remove();
                updateSelectOptions(); 
                toggleNoItemsMsg(); 
            }
        });

        // Dropdown မှာ ရွေးတဲ့အခါတိုင်း စစ်မယ်
        container.addEventListener('change', function(e) {
            if (e.target.classList.contains('item-select')) {
                updateSelectOptions();
            }
        });

        // စဖွင့်ချင်းမှာ ရှိပြီးသား Item တွေကို စစ်ဆေးမယ်
        updateSelectOptions();
    });
</script>
@endsection