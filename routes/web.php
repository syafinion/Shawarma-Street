<?php

use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', function () {
    return view('home');
});

// Cart page
Route::get('/cart', function () {
    return view('cart');
});

// Checkout page
Route::get('/checkout', function () {
    return view('checkout');
});

// Menu page
Route::get('/restaurantmenu', function () {
    return view('restaurantmenu');
});
