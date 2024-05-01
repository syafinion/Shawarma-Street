<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // For running raw SQL statements

class UpdateCartsDatabaseStructure extends Migration
{
    public function up(): void
    {
        // Insert initial data into categories and items tables using raw SQL
        DB::statement("
            INSERT INTO categories (category_id, name, description)
            VALUES
            (4, 'Shawarmas', 'Delicious Shawarmas with different flavors'),
            (5, 'Rice', 'Variety of rice dishes'),
            (6, 'Add ons', 'Extras to add to your meal'),
            (7, 'Drinks & Desserts', 'Refreshing drinks and sweet desserts')
        ");

        DB::statement("
            INSERT INTO items (category_id, name, description, price, image_url, available_status, stock)
            VALUES
            (4, 'Shawarma Original', 'Fries: RM 12.00 /No fries RM 9.00', 9.00, 'assets/img/Shawarmas/shawarma_original.png', 1, 100),
            (4, 'Shawarma Cheese', 'Fries: RM 13.00 /No fries RM 10.00', 10.00, 'assets/img/Shawarmas/shawarma_cheese.png', 1, 100),
            (4, 'Shawarma Plate', 'A full plate of Shawarma', 15.00, 'assets/img/Shawarmas/shawarma_plate.png', 1, 100),
            (5, 'Mandy Chicken', 'Delicious Mandy Chicken', 15.00, 'assets/img/shawarma menu images/Rice/Mandy Chicken Rice.png', 1, 100),
            (5, 'Vegetarian Meal', 'A meal for vegetarians', 10.00, 'assets/img/shawarma menu images/Rice/Vegetarian meal.png', 1, 100),
            (6, 'Humus', 'Delicious humus', 2.00, 'assets/img/shawarma menu images/Add ons/Hummus.png', 1, 100),
            (6, 'Green Salad', 'Fresh green salad', 2.00, 'assets/img/shawarma menu images/Add ons/Green Salad.png', 1, 100),
            (6, 'Fries', 'Crispy fries', 3.00, 'assets/img/shawarma menu images/Add ons/Fries.png', 1, 100),
            (7, 'Arabic Tea', 'Cold or Hot Arabic tea', 2.50, 'assets/img/shawarma menu images/Drinks n dessert/Arabic tea.png', 1, 100),
            (7, 'Lemon Juice', 'Freshly squeezed lemon juice', 2.00, 'assets/img/Drinks n dessert/Lemon juice.png', 1, 100),
            (7, 'Watermelon Juice', 'Refreshing watermelon juice', 5.00, 'assets/img/shawarma menu images/Drinks n dessert/Watermelon Juice.png', 1, 100),
            (7, 'Luqaimat', 'Sweet luqaimat', 8.00, 'assets/img/shawarma menu images/Drinks n dessert/Luqaimat.png', 1, 100),
            (7, 'Kunafa', 'Delicious Kunafa', 9.00, 'assets/img/shawarma menu images/Drinks n dessert/Kunafa.png', 1, 10)
        ");
    }

    public function down(): void
    {
        // Remove the inserted data
        DB::statement("DELETE FROM items WHERE category_id >= 4");
        DB::statement("DELETE FROM categories WHERE category_id >= 4");
    }
}
