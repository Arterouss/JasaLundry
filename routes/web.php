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
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':customer')
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            
            Route::get('/dashboard', [OrderController::class, 'dashboard'])->name('dashboard');
            // Cari kode ini di dalam group customer:
            Route::get('/riwayat', [OrderController::class, 'history'])->name('orders.history');
            Route::get('/pesan', [OrderController::class, 'create'])->name('orders.create');
            Route::post('/pesan', [OrderController::class, 'store'])->name('orders.store');
            Route::get('/pembayaran/{order}', [OrderController::class, 'checkout'])->name('orders.checkout');
            Route::post('/pembayaran/{order}/pay', [OrderController::class, 'processPayment'])->name('orders.pay');
            
            Route::get('/profile', function () {
                return view('customer.profile');
            })->name('profile');
    });

    // ------------------------------------------
// KELOMPOK HAK AKSES: ADMIN (KASIR/OWNER)
// ------------------------------------------
Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        // Dashboard utama admin
        Route::get('/dashboard', [AdminOrderController::class, 'index'])->name('dashboard');
        
        // 1. RUTE EDIT DINAMIS (Menggunakan URL rapi, mengarah ke Controller fungsi edit)
        Route::get('/pesanan/{id}/edit', [AdminOrderController::class, 'edit'])->name('orders.edit');
        
        // 2. RUTE PROSES SIMPAN EDIT (Sesuai parameter $id di Controller kamu)
        Route::patch('/kelola-pesanan/{id}/assess', [AdminOrderController::class, 'assessOrder'])->name('orders.assess');
        
        // 3. RUTE UPDATE STATUS INSTAN VIA AJAX
        Route::patch('/kelola-pesanan/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // 4. RUTE PROSES WALK-IN
        Route::post('/kelola-pesanan/walk-in', [AdminOrderController::class, 'storeWalkIn'])->name('orders.walk-in');

        // 5. Halaman Form Tambah Pesanan Walk-In
        Route::get('/tambah-pesanan', function () {
            return view('admin.tambah-pesanan');
        })->name('orders.create');
        
        // 6. RUTE CADANGAN (Rute statis awalmu yang bikin halaman mau nampil)
        Route::get('/edit-pesanan', function () {
            return view('admin.edit-pesanan');
        })->name('orders.edit-view');
    });
});