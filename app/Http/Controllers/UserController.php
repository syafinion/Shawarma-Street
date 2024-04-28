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


public function submitReview(Request $request)
{
    $validated = $request->validate([
        'orderId' => 'required|integer',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
    ]);

    // Prepare the query to insert the review into the database
    $sql = "INSERT INTO reviews (order_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, ?)";

    // Execute the query
    DB::insert($sql, [
        $validated['orderId'],
        Auth::id(),  // Assuming you want to record which user submitted the review
        $validated['rating'],
        $validated['comment'],
        now(),
    ]);

    return response()->json(['message' => 'Review submitted successfully!']);
}


public function getOrderDetails($orderId)
{
    // Log the incoming orderId to check its correctness
    \Log::info("Fetching details for order ID: " . $orderId);

    $order = DB::select("
        SELECT o.order_id, o.user_id, o.created_at, COALESCE(o.order_status, 'Pending') AS status, 
               o.total_price, o.customer_name, o.phone_number, 
               o.delivery_address_line1, o.delivery_address_line2, o.delivery_city, 
               o.delivery_state, o.delivery_zip_code, o.delivery_country,
               p.payment_method, p.status AS payment_status, p.processed_at
        FROM orders AS o
        LEFT JOIN payments AS p ON o.order_id = p.order_id
        WHERE o.order_id = ?
    ", [$orderId]);

    if (empty($order)) {
        \Log::error("No order found with ID: " . $orderId);
        return response()->json(['error' => 'Order not found'], 404);
    }

    $items = DB::select("
        SELECT i.name, i.description, oi.quantity, oi.price, (oi.quantity * oi.price) AS subtotal
        FROM order_items AS oi
        JOIN items AS i ON oi.item_id = i.item_id
        WHERE oi.order_id = ?
    ", [$orderId]);

    $response = [
        'order' => $order[0],
        'items' => $items
    ];

    return response()->json($response);
}


public function cancelOrder(Request $request)
{
    $orderId = $request->input('orderId');

    DB::beginTransaction();
    try {
        // Delete related reviews
        DB::delete("DELETE FROM reviews WHERE order_id = ?", [$orderId]);

        // Delete related payments
        DB::delete("DELETE FROM payments WHERE order_id = ?", [$orderId]);

        // Delete related order items
        DB::delete("DELETE FROM order_items WHERE order_id = ?", [$orderId]);

        // Finally, delete the order itself
        DB::delete("DELETE FROM orders WHERE order_id = ?", [$orderId]);

        DB::commit();
        return response()->json(['message' => 'Order and related records deleted successfully']);
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error("Failed to delete order with ID {$orderId}: " . $e->getMessage());
        return response()->json(['error' => 'Failed to delete order'], 500);
    }
}






}
