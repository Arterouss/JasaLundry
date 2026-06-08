<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminOrderController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. RUTE TAMU (Hanya bisa diakses sebelum login)
// ==========================================
Route::middleware('guest')->group(function () {
    // Halaman Awal & Login dialihkan ke AuthController agar proses POST-nya bekerja
    Route::get('/', [AuthController::class, 'showLogin']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Halaman Register
    Route::get('/register', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/register', [AuthController::class, 'signup']);
});

// ==========================================
// 2. RUTE TERPROTEKSI (Wajib Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // Proses Logout resmi
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------
    // KELOMPOK HAK AKSES: CUSTOMER (PELANGGAN)
    // ------------------------------------------
    Route::middleware('middleware:\App\Http\Middleware\RoleMiddleware:customer')
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            
            // Mengarah ke view 'dashboard' milik customer melalui Controller
            Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('dashboard');
            
            // Halaman Form Pesan Laundry (Mengarah ke view 'pesan' kamu)
            Route::get('/pesan', [OrderController::class, 'create'])->name('orders.create');
            Route::post('/pesan', [OrderController::class, 'store'])->name('orders.store');
            
            // Halaman Pembayaran Cashless (Mengarah ke view 'pembayaran' kamu)
            Route::get('/pembayaran/{order}', [OrderController::class, 'checkout'])->name('orders.checkout');
            Route::post('/pembayaran/{order}/pay', [OrderController::class, 'processPayment'])->name('orders.pay');
            
            // Halaman Profile Pelanggan
            Route::get('/profile', function () {
                return view('profile'); // Sesuaikan jika nanti mau dibuatkan controller khusus profile
            })->name('profile');
    });

    // ------------------------------------------
    // KELOMPOK HAK AKSES: ADMIN (KASIR/OWNER)
    // ------------------------------------------
    Route::middleware('middleware:\App\Http\Middleware\RoleMiddleware:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            
            // Dashboard Utama Admin (List Semua Orderan Masuk)
            Route::get('/dashboard', [AdminOrderController::class, 'index'])->name('dashboard');
            
            // Halaman Kelola Pesanan (Input timbangan berat, hitung jarak/ongkir, dll)
            Route::patch('/kelola-pesanan/{order}/assess', [AdminOrderController::class, 'assessOrder'])->name('orders.assess');
            
            // Perubahan status laundry step-by-step
            Route::patch('/kelola-pesanan/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
            
            // Pembuatan order transaksi langsung di kasir (Walk-in)
            Route::post('/kelola-pesanan/walk-in', [AdminOrderController::class, 'storeWalkIn'])->name('orders.walk-in');
    });
});