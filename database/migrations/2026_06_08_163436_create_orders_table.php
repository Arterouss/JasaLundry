<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Relasi Pelanggan (Nullable untuk menampung pelanggan spontan/walk-in)
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('walk_in_name')->nullable(); // Diisi oleh admin jika pelanggan datang langsung
            
            // Relasi ke Master Data
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('perfume_id')->constrained('perfumes');
            
            // Logika Antar Jemput & Alamat
            $table->boolean('is_pickup_delivery')->default(false);
            $table->text('gps_address')->nullable(); // Menyimpan koordinat / alamat dari GPS terkini
            
            // Logika Pembayaran
            $table->enum('payment_method', ['cash_on_site', 'cashless'])->nullable();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            
            // Input Kalkulasi Admin (Nullable di awal karena dihitung setelah ditimbang & diukur)
            $table->decimal('weight', 5, 2)->nullable(); // Berat cucian (kg)
            $table->decimal('distance_km', 5, 2)->nullable(); // Jarak pengantaran (km)
            $table->decimal('grand_total', 12, 2)->nullable(); // Total biaya keseluruhan
            
            // Status Tracking & Estimasi
            $table->string('status'); // Menyimpan state status (Diterima, Dijemput, Diproses, dll)
            $table->timestamp('estimated_completion_time')->nullable(); // Hasil kalkulasi waktu batas atas
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};