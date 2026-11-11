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
        Schema::create('booking_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('restrict');
            $table->string('from_status', 25)->nullable();
            $table->string('to_status', 25);
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->string('actor_type', 10); // penyewa, admin, sistem
            $table->text('reason')->nullable();
            $table->dateTime('changed_at');
            
            $table->index(['booking_id', 'changed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_status_logs');
    }
};
