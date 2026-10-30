<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;


// Register page
Route::get('/', function () {
    return redirect('/register');
});


Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

// Login page
Route::get('/login', function () {
    return view('login');
})->name('login');

// Login action
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    return back()
        ->withErrors(['email' => 'Invalid email or password.'])
        ->onlyInput('email');
});

// Auth protected routes
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Items (This creates items.index, items.create, items.store, etc.)
    Route::resource('items', ItemController::class);

      // Orders Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        // ... (ကျန်တဲ့ order routes တွေ အောက်မှာ ထပ်ထည့်ပါ)
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
    
});

