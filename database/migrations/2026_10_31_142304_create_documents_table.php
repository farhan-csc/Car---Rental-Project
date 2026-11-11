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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->string('type', 5); // ktp, sim
            $table->string('file_path', 255)->nullable();
            $table->string('status', 25)->default('menunggu_verifikasi');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->dateTime('verified_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

            $table->unique(['booking_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
