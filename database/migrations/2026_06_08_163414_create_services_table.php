<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Cuci+Kering, Setrika, Cuci+Setrika
            $table->decimal('price_per_kg', 10, 2); // Harga per kilogram
            $table->integer('estimated_minutes'); // Standar waktu batas atas (60, 200, 220)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};