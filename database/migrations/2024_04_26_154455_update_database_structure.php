<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDatabaseStructure extends Migration
{
    public function up()
    {
        // Add updated_at column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->after('created_at');
        });

        // Create payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('status');
            $table->timestamp('processed_at')->nullable();
        });
    }

    public function down()
    {
        // Remove updated_at column from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });

        // Drop payments table
        Schema::dropIfExists('payments');
    }
};
