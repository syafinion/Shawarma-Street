<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    DB::beginTransaction();
    try {
        $cartId = DB::selectOne("SELECT cart_id FROM carts WHERE user_id = ?", [$userId])->cart_id ?? null;

        if (!$cartId) {
            DB::insert("INSERT INTO carts (user_id, created_at) VALUES (?, NOW())", [$userId]);
            $cartId = DB::getPdo()->lastInsertId();
        }

        $existingItem = DB::selectOne("SELECT * FROM cart_items WHERE cart_id = ? AND item_id = ?", [$cartId, $request->item_id]);

        if ($existingItem) {
            DB::update("UPDATE cart_items SET quantity = quantity + 1 WHERE cart_item_id = ?", [$existingItem->cart_item_id]);
        } else {
            DB::insert("INSERT INTO cart_items (cart_id, item_id, quantity) VALUES (?, ?, 1)", [$cartId, $request->item_id]);
        }

        DB::commit();

        // Fetch updated cart items with price casted as decimal
        $cartItems = DB::select("SELECT ci.item_id, ci.quantity, CAST(i.price AS DECIMAL(10,2)) as price, i.name, i.image_url as image FROM cart_items ci JOIN items i ON ci.item_id = i.item_id WHERE ci.cart_id = ?", [$cartId]);
        return response()->json(['success' => 'Item added to cart', 'cartItems' => $cartItems]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function removeFromCart(Request $request)
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    DB::beginTransaction();
    try {
        $cartId = DB::selectOne("SELECT cart_id FROM carts WHERE user_id = ?", [$userId])->cart_id;
        DB::delete("DELETE FROM cart_items WHERE cart_id = ? AND item_id = ?", [$cartId, $request->item_id]);
        DB::commit();

        $cartItems = DB::select("SELECT ci.item_id, ci.quantity, CAST(i.price AS DECIMAL(10,2)) as price, i.name, i.image_url as image FROM cart_items ci JOIN items i ON ci.item_id = i.item_id WHERE ci.cart_id = ?", [$cartId]);
        return response()->json(['success' => 'Item removed from cart', 'cartItems' => $cartItems]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function updateItemQuantity(Request $request)
{
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }

    DB::beginTransaction();
    try {
        $cartId = DB::selectOne("SELECT cart_id FROM carts WHERE user_id = ?", [$userId])->cart_id;
        DB::update("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND item_id = ?", [$request->quantity, $cartId, $request->item_id]);
        DB::commit();

        $cartItems = DB::select("SELECT ci.item_id, ci.quantity, CAST(i.price AS DECIMAL(10,2)) as price, i.name, i.image_url as image FROM cart_items ci JOIN items i ON ci.item_id = i.item_id WHERE ci.cart_id = ?", [$cartId]);
        return response()->json(['success' => 'Cart updated', 'cartItems' => $cartItems]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
