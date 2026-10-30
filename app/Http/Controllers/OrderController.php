<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. List all orders (paginated) + Search
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::with('user')
            ->withCount('items') // Gets number of items in the order
            ->when($search, function ($query, $search) {
                // Search by ID, Status, or Item Name
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhereHas('items', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', compact('orders', 'search'));
    }

    // 2. Show form to create new order
    public function create()
    {
        $items = Item::all();
        return view('orders.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Order အရင်ဆောက်မယ်
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => 0, 
            'status' => $request->status,
        ]);

        $total = 0;
        $itemsToAttach = [];

        // ရွေးထားတဲ့ Item တွေ အကုန်လှမ်းထည့်မယ်
        foreach ($request->items as $itemData) {
            $item = Item::findOrFail($itemData['item_id']);
            $subtotal = $item->price * $itemData['quantity'];
            $total += $subtotal;

            $itemsToAttach[$item->id] = [
                'quantity' => $itemData['quantity'],
                'unit_price' => $item->price, // ✅ ဒီနေရာမှာ unit_price လို့ ပြောင်းလိုက်ပါ
            ];
        }

        // Order ထဲကို Item တွေ ချိတ်ဆက်မယ်
        $order->items()->attach($itemsToAttach);
        
        // Total ကို Update လုပ်မယ်
        $order->update(['total' => $total]);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    // 4. View single order details
    // public function show(Order $order)
    // {
    //     $order->load('user', 'items');
    //     return view('orders.show', compact('order'));
    // }
        // Show single order details (View)
    public function show(Order $order)
    {
        $order->load(['user', 'items']);
        return view('orders.show', compact('order'));
    }
        // ၁။ Edit လုပ်ဖို့ Form ပြမယ်
    // Edit Form ပြရန်
    public function edit(Order $order)
    {
        $items = \App\Models\Item::all();
        $order->load('items'); // Order ထဲက Item တွေကို ဆွဲထုတ်မယ်
        return view('orders.edit', compact('order', 'items'));
    }

    // Update လုပ်ရန် (Status နဲ့ Items တွေကို ပြင်မယ်)
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // ၁။ Status ကို အရင် Update လုပ်မယ်
        $order->update(['status' => $request->status]);

        // ၂။ Item တွေကို Update လုပ်မယ်
        $total = 0;
        $itemsToSync = [];

        foreach ($request->items as $itemData) {
            $item = \App\Models\Item::findOrFail($itemData['item_id']);
            $subtotal = $item->price * $itemData['quantity'];
            $total += $subtotal;

            $itemsToSync[$item->id] = [
                'quantity' => $itemData['quantity'],
                'unit_price' => $item->price,
            ];
        }

        // Pivot Table မှာ ရှိတဲ့ Item တွေကို Sync လုပ်မယ် (ဖျက်တာ၊ ထပ်တိုးတာ အကုန်လုပ်ပေးမယ်)
        $order->items()->sync($itemsToSync);
        
        // Total ကို Update လုပ်မယ်
        $order->update(['total' => $total]);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully.');
    }

    // 5. Delete order
    public function destroy(Order $order)
    {
        // Detach items first to clean pivot table
        $order->items()->detach();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}