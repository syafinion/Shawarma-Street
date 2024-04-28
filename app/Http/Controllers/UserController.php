<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function myAccount()
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    $userId = Auth::id();
    $user = DB::select("SELECT * FROM users WHERE user_id = ?", [$userId])[0];
    $addresses = DB::select("SELECT addresses.*, users.name AS user_name FROM addresses 
                             JOIN users ON users.user_id = addresses.user_id 
                             WHERE addresses.user_id = ?", [$userId]);

    // Join orders with order_items to count items per order
    $orders = DB::select("
        SELECT o.order_id, o.user_id, o.created_at, COALESCE(o.order_status, 'Pending') as status, o.total_price, COUNT(i.order_item_id) as item_count
        FROM orders o
        LEFT JOIN order_items i ON o.order_id = i.order_id
        WHERE o.user_id = ?
        GROUP BY o.order_id, o.user_id, o.created_at, o.order_status, o.total_price
    ", [$userId]);

    // Default empty array if no addresses or orders
    $addresses = $addresses ?: [];
    $orders = $orders ?: [];

    return view('myprofile', [
        'user' => $user,
        'addresses' => $addresses,
        'orders' => $orders
    ]);
}



public function updateAccount(Request $request)
{
    $userId = Auth::id();
    $user = Auth::user();

    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
        'phone_number' => 'nullable|max:15',
        'birthday' => 'nullable|date',
        'current-password' => 'required_with:new-password,new-password_confirmation',
        'new-password' => 'required_with:current-password|min:6|confirmed'
    ]);

    // Check current password and update if new password is provided
    if ($request->filled('current-password') && $request->filled('new-password')) {
        if (!Hash::check($request->input('current-password'), $user->password)) {
            return redirect()->back()->withErrors(['current-password' => 'Current password is incorrect.']);
        }

        $newPasswordHash = Hash::make($request->input('new-password'));
        // Use raw SQL to update the password
        DB::statement("UPDATE users SET password = ? WHERE user_id = ?", [$newPasswordHash, $userId]);
    }

    // Update user details using raw SQL
    DB::statement("UPDATE users SET name = ?, email = ?, phone_number = ?, birthday = ? WHERE user_id = ?",
        [$validatedData['name'], $validatedData['email'], $validatedData['phone_number'], $validatedData['birthday'], $userId]);

    return redirect()->back()->with('success', 'Account updated successfully.');
}

public function checkPassword(Request $request)
{
    // Removed the AJAX check for broader compatibility
    $currentPassword = $request->input('current_password');
    $user = Auth::user();

    if (!Hash::check($currentPassword, $user->password)) {
        return response()->json(['valid' => false, 'message' => 'Current password is incorrect.']);
    } else {
        return response()->json(['valid' => true]);
    }
}






    public function updateAddress(Request $request, $addressId)
    {
        $userId = Auth::id();

        // Validate the request data
        $validatedData = $request->validate([
            'address_line1' => 'required',
            'address_line2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'country' => 'required'
        ]);

        // Check if address belongs to the user
        $address = DB::select("SELECT * FROM addresses WHERE address_id = ? AND user_id = ?", [$addressId, $userId])[0];
        if (!$address) {
            return back()->with('error', 'Address not found.');
        }

        // Update the address
        DB::update("UPDATE addresses SET address_line1 = ?, address_line2 = ?, city = ?, state = ?, zip_code = ?, country = ? WHERE address_id = ?",
            [$validatedData['address_line1'], $validatedData['address_line2'], $validatedData['city'], $validatedData['state'], $validatedData['zip_code'], $validatedData['country'], $addressId]);

        return back()->with('success', 'Address updated successfully.');
    }

    public function addAddress(Request $request)
{
    $userId = Auth::id();

    // Validate the request data
    $validatedData = $request->validate([
        'address_line1' => 'required',
        'address_line2' => 'nullable',
        'city' => 'required',
        'state' => 'required',
        'zip_code' => 'required',
        'country' => 'required'
    ]);

    // Insert new address into database
    DB::insert("INSERT INTO addresses (user_id, address_line1, address_line2, city, state, zip_code, country) VALUES (?, ?, ?, ?, ?, ?, ?)",
        [$userId, $validatedData['address_line1'], $validatedData['address_line2'], $validatedData['city'], $validatedData['state'], $validatedData['zip_code'], $validatedData['country']]);

    return back()->with('success', 'Address added successfully.');
}

}
