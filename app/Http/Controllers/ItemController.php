<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // List all items (paginated) + Search
    public function index(Request $request)
    {
        $search = $request->input('search');

        $items = Item::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->withCount('orders') // Gets the number of orders for the "Orders" column
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('items.index', compact('items', 'search'));
    }

    // Show create form
    public function create()
    {
        return view('items.create');
    }

    

    // View a single item
    // // public function show(Item $item)
    // {
    //     $item->loadCount('orders');
    //     return view('items.show', compact('item'));
    // }
    //  public function show(Item $item)
    // {
    //     // Load orders with user, pivot data, and paginate if needed (here we get all related orders)
    //     $orders = $item->orders()
    //         ->with('user')
    //         ->withPivot('quantity', 'unit_price')
    //         ->latest()
    //         ->get();

    //     return view('items.show', compact('item', 'orders'));
    // }
    public function show(Request $request, Item $item)
    {
        $search = $request->input('search');

        $orders = $item->orders()
            ->with('user')
            ->withPivot('quantity', 'unit_price')
            ->when($search, function($query, $search) {
                $query->where('orders.id', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->get();

        return view('items.show', compact('item', 'orders'));
    }
    // Show edit form
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    // Update item
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }
 // ✅ ဒီ store() method ကို ထည့်ဖို့ လိုအပ်နေပါတယ် (ဒါမှ Save လုပ်လို့ရမှာပါ)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Auto-generate SKU 
        $data['sku'] = 'SKU-' . strtoupper(uniqid());

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }
    // Delete item (block if used in order)
    public function destroy(Item $item)
    {
        if ($item->orders()->exists()) {
            return redirect()->route('items.index')
                ->with('error', 'Cannot delete this item because it is used in one or more orders.');
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}