<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminOrderController;
use Illuminate\Support\Facades\Route;

// Rute untuk melempar user ke halaman Google
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');

// Rute callback penampung kembalian dari Google
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

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
    // ------------------------------------------
// KELOMPOK HAK AKSES: CUSTOMER (PELANGGAN)
// ------------------------------------------
// Perbaikan: Langsung panggil Class Middleware-nya tanpa embel-embel string 'middleware:' di depan
Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':customer')
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        
        Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('dashboard');
        Route::get('/pesan', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/pesan', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/pembayaran/{order}', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/pembayaran/{order}/pay', [OrderController::class, 'processPayment'])->name('orders.pay');
        
        Route::get('/profile', function () {
            return view('customer.profile');
        })->name('profile');

        Route::get('/riwayat', function () {
            return view('customer.riwayat');
        })->name('riwayat');
});

// ------------------------------------------
// KELOMPOK HAK AKSES: ADMIN (KASIR/OWNER)
// ------------------------------------------
// Perbaikan: Langsung panggil Class Middleware-nya tanpa embel-embel string 'middleware:' di depan
Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        Route::get('/dashboard', [AdminOrderController::class, 'index'])->name('dashboard');
        Route::patch('/kelola-pesanan/{order}/assess', [AdminOrderController::class, 'assessOrder'])->name('orders.assess');
        Route::patch('/kelola-pesanan/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/kelola-pesanan/walk-in', [AdminOrderController::class, 'storeWalkIn'])->name('orders.walk-in');

        Route::get('/tambah-pesanan', function () {
            return view('admin.tambah-pesanan');
        });
        
        Route::get('/edit-pesanan', function () {
            return view('admin.edit-pesanan');
        });
    });
});