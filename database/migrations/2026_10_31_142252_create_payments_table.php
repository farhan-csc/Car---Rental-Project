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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('restrict');
            $table->string('type', 15); // dp, pelunasan, denda
            $table->bigInteger('amount');
            $table->string('method', 10); // transfer, tunai
            $table->string('proof_path', 255)->nullable();
            $table->string('status', 25)->default('menunggu_verifikasi');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->dateTime('verified_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
