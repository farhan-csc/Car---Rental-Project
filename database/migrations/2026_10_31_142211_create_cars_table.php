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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('brand', 50);
            $table->string('plate_number', 15)->unique();
            $table->smallInteger('year')->unsigned();
            $table->string('transmission', 10); 
            $table->smallInteger('seats')->unsigned();
            $table->bigInteger('price_per_day');
            $table->string('status', 15)->default('aktif'); 
            $table->text('description')->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
