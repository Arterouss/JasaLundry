<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/pesan', function () {
    return view('pesan');
});

Route::get('/pembayaran', function () {
    return view('pembayaran');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/kelola-pesanan', function () {
    return view('kelola-pesanan');
});
