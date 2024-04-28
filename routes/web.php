<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\DB;

Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        $categories = DB::table('categories')->get();
        return view('home', ['categories' => $categories]);
    });

    Route::get('/cart', function () {
        $categories = DB::table('categories')->get();
        return view('cart', ['categories' => $categories]);
    });

    Route::get('/404', function () {
        $categories = DB::table('categories')->get();
        return view('404', ['categories' => $categories]);
    });

    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('checkout');

    Route::get('/myprofile', [UserController::class, 'myAccount'])->name('my-profile');

    Route::get('/restaurantmenu', [ItemController::class, 'showItems'])->name('restaurant-menu');

    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class, 'updateItemQuantity'])->name('cart.update');
    Route::get('/cart/show', [CartController::class, 'getCart'])->name('cart.show');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User account management
    Route::get('/my-account', [UserController::class, 'myAccount'])->name('my-account');
    Route::post('/update-account', [UserController::class, 'updateAccount'])->name('update-account');
    Route::post('/update-address/{addressId}', [UserController::class, 'updateAddress'])->name('update-address');
    Route::post('/add-address', [UserController::class, 'addAddress'])->name('add-address');
    Route::post('/check-password', [UserController::class, 'checkPassword'])->name('check-password');
    
    Route::post('/submit-order', [OrderController::class, 'submitOrder'])->name('submitOrder');

    // Payment Page (display form, should be GET)
    Route::get('/payment-details', [PaymentController::class, 'showPaymentForm'])->name('paymentPage');

    // Process Payment (process the form, should be POST)
    Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('processPayment');
    
    Route::get('/search', [ItemController::class, 'showItems'])->name('search');

    Route::post('/cart/update-order-type', [CartController::class, 'updateOrderType']);

});


