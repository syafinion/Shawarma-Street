<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function showItems(Request $request)
    {
        $query = "SELECT items.*, categories.name AS category_name FROM items 
                  INNER JOIN categories ON items.category_id = categories.category_id 
                  WHERE items.available_status = 1";

        if ($request->has('query')) {
            $searchTerm = $request->input('query');
            // Escape the search term to prevent SQL injection
            $searchTerm = addslashes($searchTerm);
            $query .= " AND items.name LIKE '%" . $searchTerm . "%'";
        }

        if ($request->has('category') && $request->category != '') {
            $categoryId = $request->input('category');
            // Cast the category ID to an integer to prevent SQL injection
            $categoryId = (int) $categoryId;
            $query .= " AND items.category_id = " . $categoryId;
        }

        // Execute the raw SQL query
        $items = DB::select($query);
        // Get all categories
        $categories = DB::select('SELECT * FROM categories');

        // Return view with items and categories data
        return view('restaurantmenu', [
            'items' => $items,
            'categories' => $categories
        ]);
    }
}


