@extends('layouts.app')

@section('title', 'New Order')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('orders.index') }}">Orders</a> / Create
</div>

<div class="page-header">
    <div class="page-title">
        <h1>New order</h1>
        <p>Add items and quantities for this order.</p>
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

<form action="{{ route('orders.store') }}" method="POST" id="order-form">
    @csrf
    
    <div class="form-card" style="max-width: 800px;">
        
        <div class="form-field">
            <label for="status">Status</label>
            <select id="status" name="status" required style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px; background:white;">
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
                <option value="Pending">Processing</option>
                <option value="Completed">Cancelled</option>
            </select>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 32px; margin-bottom: 16px;">
            <label style="font-size: 14px; font-weight: 500;">Items</label>
            <button type="button" id="add-item-btn" class="btn-secondary" style="padding: 6px 12px; font-size: 13px;">Add Item</button>
        </div>

        <div id="items-container">
            <!-- စဖွင့်ချင်းမှာ Box တွေ မပါပါဘူး -->
        </div>

        <!-- Box မရှိရင် ပြမယ့် စာသား -->
        <div id="no-items-msg" style="color: #6b7280; font-size: 14px; padding: 10px 0;">
            Click Add Item to include products in this order.
        </div>

        <div class="form-actions" style="margin-top: 32px;">
            <button type="submit" class="btn-new">Create order</button>
            <a href="{{ route('orders.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemIndex = 0;
        const container = document.getElementById('items-container');
        const addBtn = document.getElementById('add-item-btn');
        const noItemsMsg = document.getElementById('no-items-msg');
        
        // Dropdown ထဲက Option တွေကို မှတ်ထားမယ်
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

        // Dropdown တွေမှာ ရွေးပြီးသား Item တွေကို ဖျောက်ပေးတဲ့ Function
        function updateSelectOptions() {
            const selects = document.querySelectorAll('.item-select');
            
            // လက်ရှိ ရွေးထားတဲ့ Value တွေကို ယူမယ်
            const selectedValues = Array.from(selects)
                .map(select => select.value)
                .filter(val => val !== "");

            selects.forEach(select => {
                const currentValue = select.value; // လက်ရှိ Box ရဲ့ အရွေးကို မှတ်ထားမယ်
                
                // Option တွေကို ပြန် Reset ချမယ်
                select.innerHTML = originalOptions;
                
                // တခြား Box တွေမှာ ရွေးပြီးသား Item တွေကို ဖျောက်မယ်
                Array.from(select.options).forEach(option => {
                    if (option.value !== "" && selectedValues.includes(option.value) && option.value !== currentValue) {
                        option.style.display = 'none';
                    }
                });

                // ⚠️ အရေးကြီးဆုံးအချက် - Reset ချပြီးတာနဲ့ အရင်ရွေးထားတဲ့ Value ကို ပြန်ပြီး သတ်မှတ်ပေးရမယ်
                // ဒါမှသာ သင်ရွေးလိုက်တဲ့ Data က Box ထဲမှာ ပြန်ပေါ်နေမှာပါ
                select.value = currentValue;
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
            
            // Box အသစ်ထည့်ပြီးတာနဲ့ ရွေးထားတဲ့အရာတွေကို စစ်မယ်
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

        // Dropdown မှာ ရွေးတဲ့အခါတိုင်း ကျန်တဲ့ Box တွေမှာ ဖျောက်ဖို့ လုပ်မယ်
        container.addEventListener('change', function(e) {
            if (e.target.classList.contains('item-select')) {
                updateSelectOptions();
            }
        });

        // Form မပို့ခင် Error စစ်ဆေးခြင်း
        document.getElementById('order-form').addEventListener('submit', function(e) {
            if (container.children.length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the order.');
                return;
            }

            let isValid = true;
            let selectedItems = [];

            document.querySelectorAll('.item-select').forEach(select => {
                if (select.value === "") {
                    isValid = false;
                } else {
                    if (selectedItems.includes(select.value)) {
                        isValid = false;
                    }
                    selectedItems.push(select.value);
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please select an item for every row. Duplicate items are not allowed.');
            }
        });
    });
</script>
@endsection