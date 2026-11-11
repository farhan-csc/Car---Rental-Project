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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->string('guest_name', 100)->nullable();
            $table->string('guest_phone', 20)->nullable();
            $table->string('guest_email', 150)->nullable();
            $table->foreignId('car_id')->constrained('cars')->onDelete('restrict');
            $table->dateTime('pickup_at');
            $table->dateTime('return_due_at');
            $table->smallInteger('rental_days')->unsigned();
            $table->bigInteger('price_per_day');
            $table->bigInteger('total_amount');
            $table->string('payment_option', 10); // lunas, dp
            $table->bigInteger('dp_amount')->nullable();
            $table->string('status', 25)->default('menunggu_pembayaran');
            $table->dateTime('payment_deadline');
            $table->string('payment_token', 64)->unique();
            $table->dateTime('policy_accepted_at')->nullable();
            $table->dateTime('picked_up_at')->nullable();
            $table->dateTime('returned_at')->nullable();
            $table->text('pickup_note')->nullable();
            $table->text('return_note')->nullable();
            $table->smallInteger('late_days')->unsigned()->default(0);
            $table->bigInteger('late_fee')->default(0);
            $table->text('cancel_reason')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->bigInteger('refund_amount')->nullable();
            $table->timestamps();

            $table->index(['car_id', 'pickup_at', 'return_due_at']);
            $table->index('status');
            $table->index('user_id');
            $table->index(['status', 'payment_deadline']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
