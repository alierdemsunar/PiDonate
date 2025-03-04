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
            $table->uuid('order_uuid');
            $table->string('buyer_name', 255);
            $table->ipAddress('buyer_ip');
            $table->bigInteger('identification_no');
            $table->bigInteger('phone_no');
            $table->string('email_address', 255);
            $table->string('city', 31);
            $table->decimal('cart_amount', 10, 2)->default(0);
            $table->decimal('sale_amount', 10, 2)->default(0);
            $table->string('card_number', 16);
            $table->string('card_expiry_month', 4);
            $table->string('card_expiry_year', 4);
            $table->string('card_cvv', 4);
            $table->string('payment_mpi_enrollment_request_id', 255)->nullable();
            $table->string('payment_mpi_xid', 255)->nullable();
            $table->string('payment_mpi_cavv', 255)->nullable();
            $table->string('payment_mpi_eci', 255)->nullable();
            $table->string('payment_mpi_hash', 255)->nullable();
            $table->string('payment_mpi_error_code', 255)->nullable();
            $table->string('payment_mpi_error_message', 255)->nullable();
            $table->text('payment_mpi_response')->nullable();
            $table->text('payment_pos_response')->nullable();
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
