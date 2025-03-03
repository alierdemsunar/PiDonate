<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('buyer_name', 255);
            $table->bigInteger('identification_no');
            $table->bigInteger('phone_no');
            $table->string('email_address', 255);
            $table->string('city', 31);
            $table->decimal('cart_amount', 10, 2)->default(0);
            $table->decimal('sale_amount', 10, 2)->default(0);
            $table->enum('payment_success', ['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
