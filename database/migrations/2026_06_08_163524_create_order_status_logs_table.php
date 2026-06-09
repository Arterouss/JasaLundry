<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_logs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel orders, jika order dihapus maka log ikut terhapus
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('status'); // Menyimpan status saat perubahan terjadi
            
            // HAPUS dua baris manual sebelumnya, GANTI dengan satu baris standar ini:
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_logs');
    }
};