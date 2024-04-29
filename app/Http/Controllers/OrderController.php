<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // The function to display all the information in checkout page
    public function showCheckout()
    {
        // Redirect to login if the user is not authenticated
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        $userId = Auth::id(); // Get the authenticated user's ID
        $user = DB::table('users')->where('user_id', $userId)->first(); // Fetch user info from the database
        $address = DB::table('addresses')->where('user_id', $userId)->first();  // Fetch user's address from the database
        $cartItems = $this->getCartItems($userId);// Get items in the user's cart
        $cart = DB::table('carts')->where('user_id', $userId)->first(); // Fetch cart info

        // If the cart is empty, show a message on the checkout page
        if (empty($cartItems)) {
            return view('checkout')->with('message', 'Your cart is empty');
        }

        // Render the checkout view with relevant data
        return view('checkout', [
            'user' => $user,
            'address' => $address,
            'cartItems' => $cartItems,
            'orderType' => $cart ? $cart->order_type : 'dine_in'
        ]);
    }

 // Helper function to get cart items for a given user
    private function getCartItems($userId)
    {
        return DB::select('
            SELECT items.name, items.price, cart_items.quantity, items.item_id, items.image_url
            FROM cart_items
            JOIN items ON cart_items.item_id = items.item_id
            JOIN carts ON cart_items.cart_id = carts.cart_id
            WHERE carts.user_id = ?', [$userId]);
    }

    // Method to submit an order
    public function submitOrder(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|numeric',
        'street_address' => 'required|string|max:255',
        'apartment' => 'nullable|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'zip_code' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'payment_method' => 'required|string',
        'order_notes' => 'nullable|string|max:1000',
    ]);

    $userId = Auth::id(); // Get the authenticated user's ID
    if (!$userId) {
        return redirect('/login')->with('error', 'Please log in to continue.');
    }

    $cartItems = $this->getCartItems($userId); // Get cart items
    if (!$cartItems) {
        return back()->with('error', 'Your cart is empty.');
    } 

    $orderType = DB::table('carts')->where('user_id', $userId)->value('order_type');
    $baseTotal = array_reduce($cartItems, function ($carry, $item) {
        return $carry + ($item->price * $item->quantity);
    }, 0); // Calculate total cost of items

    // Include shipping fee for delivery orders
    $shippingFee = 0;
    if ($orderType === 'delivery') {
        $shippingFee = 5.00; // RM5 shipping fee
    }
    $totalPrice = $baseTotal + $shippingFee; // Total price including shipping

    DB::beginTransaction(); // Start a transaction for order processing
    try {
        $now = now()->toDateTimeString(); // Current timestamp
       // Insert a new order record
        $insertOrderSQL = "INSERT INTO orders (user_id, order_type, customer_name, phone_number, total_price, order_status, order_notes, created_at, delivery_address_line1, delivery_address_line2, delivery_city, delivery_state, delivery_zip_code, delivery_country) VALUES (?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?)";
        DB::insert($insertOrderSQL, [
            $userId, $orderType, $validated['name'], $validated['phone_number'], $totalPrice, $validated['order_notes'], $now,
            $validated['street_address'], $validated['apartment'], $validated['city'], $validated['state'], $validated['zip_code'], $validated['country']
        ]);
        $orderId = DB::getPdo()->lastInsertId(); // Get the order ID of the newly inserted order

        // Insert records for each item in the order

        foreach ($cartItems as $item) {
            DB::insert("INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)", [$orderId, $item->item_id, $item->quantity, $item->price]);
            DB::update("UPDATE items SET stock = stock - ? WHERE item_id = ?", [$item->quantity, $item->item_id]);
        }

        // Clear the cart items and the cart
        $cartId = DB::table('carts')->where('user_id', $userId)->value('cart_id');
        DB::delete("DELETE FROM cart_items WHERE cart_id = ?", [$cartId]);
        DB::delete("DELETE FROM carts WHERE user_id = ?", [$userId]);

        // Handle payment processing
        $paymentTransactionId = ($validated['payment_method'] === 'pay_at_counter') ? 'CTR-' . str_pad($orderId, 10, '0', STR_PAD_LEFT) : null;
        DB::update("UPDATE orders SET payment_transaction_id = ? WHERE order_id = ?", [$paymentTransactionId, $orderId]);

        // Insert a payment record for the order

        $insertPaymentSQL = "INSERT INTO payments (order_id, amount, payment_method, status, processed_at) VALUES (?, ?, ?, 'pending', ?)";
        DB::insert($insertPaymentSQL, [$orderId, $totalPrice, $validated['payment_method'], $now]);

        DB::commit(); // Commit the transaction

        session()->flash('orderSuccess', 'Your order has been successfully placed.');
        
        // Redirect based on payment method
        if ($validated['payment_method'] === 'pay_at_counter') {
            return redirect('/myprofile')->with('success', 'Order processed successfully at the counter.');
        } else {
            return redirect()->route('paymentPage', ['order_id' => $orderId, 'payment_method' => $validated['payment_method']]);
        }
    } catch (\Exception $e) {
        DB::rollback(); // Rollback the transaction in case of an error
        Log::error('Error placing order', ['error' => $e->getMessage(), 'user_id' => $userId]);
        return back()->with('error', 'Error placing order: ' . $e->getMessage());
    }
}



    

    

}
