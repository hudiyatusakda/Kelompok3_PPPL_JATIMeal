<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PreferenceController;

Route::middleware('guest')->group(function () {

    Route::get('/', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/personalize', [PreferenceController::class, 'index'])->name('personal.index');
    Route::post('/personalize', [PreferenceController::class, 'store'])->name('personal.store');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return "<h1>Selamat Datang di Dashboard Admin</h1><p>Hanya admin yang bisa lihat ini.</p>
        <form action='" . route('logout') . "' method='POST'>" . csrf_field() . "<button type='submit'>Logout Admin</button></form>";
    })->name('admin.dashboard');
});
