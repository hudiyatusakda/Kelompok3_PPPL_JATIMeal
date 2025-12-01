<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

// Route::get('/', function () {
//     return view('register');
// });

Route::get('/register', [RegisterController::class, 'index'])->name('register.index');

// Route untuk memproses form (ini yang dipanggil di action form Anda)
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', function () {
    return view('login');
});

Route::get('/Hal_Utama', function () {
    return view('Hal_Utama');
});