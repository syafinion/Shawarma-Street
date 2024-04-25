<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function showItems()
    {
        // Raw SQL Query to fetch items
        $sql = "SELECT * FROM items WHERE available_status = 1"; // assuming you only want to show available items
        $items = DB::select($sql);

        // Return view with items data
        return view('restaurantmenu', ['items' => $items]);
    }
}
