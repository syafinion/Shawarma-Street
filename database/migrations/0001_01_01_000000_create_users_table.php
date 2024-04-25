<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->string('phone_number', 15)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('deleted_at')->nullable();
        });

        // Categories Table
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name');
            $table->text('description')->nullable();
        });

        // Items Table
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('category_id')->constrained('categories', 'category_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image_url')->nullable();
            $table->boolean('available_status')->default(true);
            $table->integer('stock');
        });

        // Carts Table
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Cart Items Table
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->foreignId('cart_id')->constrained('carts', 'cart_id');
            $table->foreignId('item_id')->constrained('items', 'item_id');
            $table->integer('quantity');
        });

        // Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->decimal('total_price', 10, 2);
            $table->string('order_status');
            $table->string('payment_transaction_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Order Items Table
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->foreignId('item_id')->constrained('items', 'item_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
        });

        // Addresses Table
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('address_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('country');
        });

        // Indexes
        Schema::table('items', function (Blueprint $table) {
            $table->index('category_id', 'idx_category');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id', 'idx_user');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->index('user_id', 'idx_cart_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('items');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
    }
};
