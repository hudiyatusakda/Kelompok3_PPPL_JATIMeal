<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

// Route::get('/', function () {
//     return view('Load');
// });

// Route::get('/login', function () {
//     return view('login');
// });

use App\Http\Controllers\PreferenceController;

Route::middleware('auth')->group(function () {
    Route::get('/personalize', [PreferenceController::class, 'index'])->name('personal.index');
    Route::post('/personalize', [PreferenceController::class, 'store'])->name('personal.store');

    Route::get('/login', function () {
    })->name('login');
});

Route::get('/', [RegisterController::class, 'index'])->name('register.index');

Route::post('/', [RegisterController::class, 'store'])->name('register.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

// Route untuk Member (Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});
