<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{

    // Retrieve the cart items for the authenticated user.
    public function getCart()
    {
        $userId = Auth::id();
        if (!$userId) {
            // Return error if user is not authenticated
            return response()->json(['error' => 'User not authenticated'], 401);
        }
// SQL to fetch cart items along with item details
        $sql = "SELECT ci.item_id, ci.quantity, CAST(i.price AS DECIMAL(10,2)) as price, i.name, i.image_url as image, c.order_type 
                FROM cart_items ci 
                JOIN items i ON ci.item_id = i.item_id 
                JOIN carts c ON ci.cart_id = c.cart_id
                WHERE c.user_id = ?";
        $cartItems = DB::select($sql, [$userId]);

        return response()->json(['cartItems' => $cartItems]);
    }



//  Add an item to the user's cart or increment its quantity if it already exists.
    public function addToCart(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            // Return error if user is not authenticated
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $orderType = $request->input('order_type', 'dine_in');
        DB::beginTransaction();
        try {
            // Check if the user already has a cart
            $cartId = DB::selectOne("SELECT cart_id FROM carts WHERE user_id = ?", [$userId])->cart_id ?? null;

            if (!$cartId) {
                // Create a new cart if not exists
                DB::insert("INSERT INTO carts (user_id, created_at, order_type) VALUES (?, NOW(), ?)", [$userId, $orderType]);
                $cartId = DB::getPdo()->lastInsertId();
            } else {
                // Update the existing cart with the new order type if it is different.
                DB::update("UPDATE carts SET order_type = ? WHERE cart_id = ?", [$orderType, $cartId]);
            }
 // Check if the item is already in the cart
            $existingItem = DB::selectOne("SELECT * FROM cart_items WHERE cart_id = ? AND item_id = ?", [$cartId, $request->item_id]);

            if ($existingItem) {
                // Increment the quantity of the existing item
                DB::update("UPDATE cart_items SET quantity = quantity + 1 WHERE cart_item_id = ?", [$existingItem->cart_item_id]);
            } else {
                 // Add new item to the cart
                DB::insert("INSERT INTO cart_items (cart_id, item_id, quantity) VALUES (?, ?, 1)", [$cartId, $request->item_id]);
            }

            DB::commit();

            $cartItems = DB::select("SELECT ci.item_id, ci.quantity, CAST(i.price AS DECIMAL(10,2)) as price, i.name, i.image_url as image FROM cart_items ci JOIN items i ON ci.item_id = i.item_id WHERE ci.cart_id = ?", [$cartId]);
            return response()->json(['success' => 'Item added to cart', 'cartItems' => $cartItems]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update the order type for the user's cart.
    public function updateOrderType(Request $request)
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $orderType = $request->order_type;

    try {
        $cart = DB::table('carts')
                  ->where('user_id', $userId)
                  ->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        DB::table('carts')
          ->where('cart_id', $cart->cart_id)
          ->update(['order_type' => $orderType]);

        return response()->json(['success' => 'Order type updated to ' . $orderType]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


// Remove an item from the user's cart.
public function removeFromCart(Request $request)
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    DB::beginTransaction();
    try {
        $cartId = DB::selectOne("SELECT cart_id FROM carts WHERE user_id = ?", [$userId])->cart_id;
        if (!$cartId) {
            DB::rollback();
            return response()->json(['error' => 'Cart not found'], 404);
        }
        // SQL query for deletion
        $deleteCount = DB::delete("DELETE FROM cart_items WHERE cart_id = ? AND item_id = ?", [$cartId, $request->item_id]);
        if ($deleteCount == 0) {
            DB::rollback();
            return response()->json(['error' => 'No items deleted'], 404);
        }

        DB::commit();

        $cartItems = DB::select("SELECT ci.item_id, ci.quantity, CAST(i.price AS DECIMAL(10,2)) as price, i.name, i.image_url as image FROM cart_items ci JOIN items i ON ci.item_id = i.item_id WHERE ci.cart_id = ?", [$cartId]);
        return response()->json(['success' => 'Item removed from cart', 'cartItems' => $cartItems]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

// Update the quantity of an item in the user's cart.
public function updateItemQuantity(Request $request)
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    $newQuantity = (int) $request->quantity;
    $itemId = $request->item_id;

    DB::beginTransaction();
    try {
        $cartId = DB::selectOne("SELECT cart_id FROM carts WHERE user_id = ?", [$userId])->cart_id;
        if (!$cartId) {
            DB::rollback();
            return response()->json(['error' => 'Cart not found'], 404);
        }

        $item = DB::selectOne("SELECT quantity FROM cart_items WHERE cart_id = ? AND item_id = ?", [$cartId, $itemId]);
        if (!$item) {
            DB::rollback();
            return response()->json(['error' => 'Item not found in cart'], 404);
        }

        // Set the quantity directly
        if ($newQuantity < 0) {
            DB::rollback();
            return response()->json(['error' => 'Cannot set quantity below zero'], 400);
        }

        DB::update("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND item_id = ?", [$newQuantity, $cartId, $itemId]);
        DB::commit();

        // Fetch updated cart items
        $cartItems = DB::select("SELECT ci.item_id, ci.quantity, CAST(i.price AS DECIMAL(10,2)) as price, i.name, i.image_url as image FROM cart_items ci JOIN items i ON ci.item_id = i.item_id WHERE ci.cart_id = ?", [$cartId]);
        return response()->json(['success' => 'Cart updated', 'cartItems' => $cartItems]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}




}
