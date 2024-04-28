<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function showCheckout()
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        $userId = Auth::id();
        $user = DB::select('SELECT * FROM users WHERE user_id = ?', [$userId])[0] ?? null;
        $address = DB::select('SELECT * FROM addresses WHERE user_id = ?', [$userId])[0] ?? null;
        $cartItems = $this->getCartItems($userId);

        if (empty($cartItems)) {
            return view('checkout')->with('message', 'Your cart is empty');
        }

        return view('checkout', [
            'user' => $user,
            'address' => $address,
            'cartItems' => $cartItems,
        ]);
    }

    private function getCartItems($userId)
    {
        return DB::select('
            SELECT items.name, items.price, cart_items.quantity, items.item_id, items.image_url
            FROM cart_items
            JOIN items ON cart_items.item_id = items.item_id
            JOIN carts ON cart_items.cart_id = carts.cart_id
            WHERE carts.user_id = ?', [$userId]);
    }

    public function submitOrder(Request $request)
    {
        $userId = Auth::id();
        $paymentMethod = $request->input('payment_method');
        $cartItems = $this->getCartItems($userId);
    
        if (!$cartItems) {
            return back()->with('error', 'Your cart is empty.');
        }
    
        $totalPrice = array_reduce($cartItems, function ($carry, $item) {
            return $carry + ($item->price * $item->quantity);
        }, 0);
    
        DB::beginTransaction();
        try {
            $now = now()->toDateTimeString();
            $insertOrderSQL = "INSERT INTO orders (user_id, customer_name, phone_number, total_price, order_status, order_notes, created_at, delivery_address_line1, delivery_address_line2, delivery_city, delivery_state, delivery_zip_code, delivery_country) VALUES (?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?)";
            DB::insert($insertOrderSQL, [
                $userId, $request->name, $request->phone_number, $totalPrice, $request->order_notes, $now, $request->street_address, $request->apartment, $request->city, $request->state, $request->zip_code, $request->country
            ]);
            $orderId = DB::getPdo()->lastInsertId();
    
            foreach ($cartItems as $item) {
                $insertItemSQL = "INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
                DB::insert($insertItemSQL, [$orderId, $item->item_id, $item->quantity, $item->price]);
            }
    
            // Create a transaction ID for all payment types, including 'pay at counter'
            $paymentTransactionId = ($paymentMethod === 'pay_at_counter') ? 'CTR-' . str_pad($orderId, 10, '0', STR_PAD_LEFT) : null;
            $updateOrderSQL = "UPDATE orders SET payment_transaction_id = ? WHERE order_id = ?";
            DB::update($updateOrderSQL, [$paymentTransactionId, $orderId]);
    
            // Insert into payments table
            $insertPaymentSQL = "INSERT INTO payments (order_id, amount, payment_method, status, processed_at) VALUES (?, ?, ?, 'pending', ?)";
            DB::insert($insertPaymentSQL, [$orderId, $totalPrice, $paymentMethod, $now]);
    
            DB::commit();
    
            if ($paymentMethod === 'pay_at_counter') {
                return redirect('/myprofile')->with('success', 'Order processed successfully at the counter.');
            } else {
                return redirect()->route('paymentPage', ['order_id' => $orderId, 'payment_method' => $paymentMethod]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error placing order', ['error' => $e->getMessage(), 'user_id' => $userId]);
            return back()->with('error', 'Error placing order: ' . $e->getMessage());
        }
    }
    

}
