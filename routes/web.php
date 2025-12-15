<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;

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

    // User dashbaord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu/kategori/{category}', [DashboardController::class, 'showCategory'])->name('menu.category');
    Route::get('/menu/{id}', [DashboardController::class, 'showMenu'])->name('menu.show');

    Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');

    Route::get('/menu/list', [MenuController::class, 'index'])->name('menu.index');

    Route::get('/admin/dashboard', function () {
        return view('adminF.dash_admin');
    })->name('admin.dashboard');

    // List menu
    Route::get('/menu/tambah', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/list', [MenuController::class, 'index'])->name('menu.index');

    // Edit menu
    Route::get('/menu/edit/{id}', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/update/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/delete/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    // admin
    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/pengguna', [AdminController::class, 'index'])->name('admin.users');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/pengguna', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/admin/pengguna/{id}', [AdminController::class, 'show'])->name('admin.users.show');
    });
});
