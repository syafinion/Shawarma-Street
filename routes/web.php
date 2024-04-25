<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;

// Group all routes that require web middleware
Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('home');
    });

    Route::get('/cart', function () {
        return view('cart');
    });

    Route::get('/checkout', function () {
        return view('checkout');
    });

    // Corrected: Only one route to handle /restaurantmenu
    Route::get('/restaurantmenu', [ItemController::class, 'showItems'])->name('restaurant-menu');

    // Authentication routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
