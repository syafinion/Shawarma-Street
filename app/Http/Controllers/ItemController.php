<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function showItems(Request $request)
    {
        $query = "SELECT items.*, categories.name AS category_name, items.stock FROM items 
                  INNER JOIN categories ON items.category_id = categories.category_id";

        if ($request->has('query')) {
            $searchTerm = $request->input('query');
            $searchTerm = addslashes($searchTerm);  // Safe guarding against SQL injection
            $query .= " WHERE items.name LIKE '%" . $searchTerm . "%'";
        } else {
            $query .= " WHERE 1=1";  // This will allow for additional conditions without WHERE syntax errors
        }

        if ($request->has('category') && $request->category != '') {
            $categoryId = $request->input('category');
            $categoryId = (int) $categoryId;  // Casting to integer for security
            $query .= " AND items.category_id = " . $categoryId;
        }

        $items = DB::select($query);
        $categories = DB::select('SELECT * FROM categories');

        return view('restaurantmenu', [
            'items' => $items,
            'categories' => $categories
        ]);
    }
}
