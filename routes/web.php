<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeeklyPlanController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\FavoriteController;

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



    // Menambahkan menu
    Route::get('/menu/tambah', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store');

    // List Menu
    Route::get('/menu/list', [MenuController::class, 'index'])->name('menu.index');

    // Route untuk Edit & Delete & Update
    Route::get('/menu/edit/{id}', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/update/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/delete/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    // Route Kategori
    Route::get('/menu/kategori/{category}', [DashboardController::class, 'showCategory'])->name('menu.category');

    // Route detail menu
    Route::get('/menu/{id}', [DashboardController::class, 'showMenu'])->name('menu.show');

    // route dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/dashboard', function () {
        return view('adminF.dash_admin');
    })->name('admin.dashboard');

    // admin PENGGUNA
    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/pengguna', [AdminController::class, 'index'])->name('admin.users');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/pengguna', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/admin/pengguna/{id}', [AdminController::class, 'show'])->name('admin.users.show');
    });

    // HALAMAN OVERVIEW (BULANAN)
    Route::get('/paket-mingguan', [WeeklyPlanController::class, 'index'])->name('weekly.index');
    Route::get('/paket-mingguan/detail', [WeeklyPlanController::class, 'showWeek'])->name('weekly.show');
    Route::post('/paket-mingguan/add', [WeeklyPlanController::class, 'store'])->name('weekly.store');
    Route::get('/paket-mingguan/{id}/edit', [WeeklyPlanController::class, 'edit'])->name('weekly.edit');
    Route::delete('/paket-mingguan/{id}', [WeeklyPlanController::class, 'destroy'])->name('weekly.destroy');
    Route::post('/paket-mingguan/{id}/complete', [WeeklyPlanController::class, 'complete'])->name('weekly.complete');

    // HALAMAN RIWAYAT
    Route::get('/riwayat-menu', [HistoryController::class, 'index'])->name('history.index');
    Route::post('/riwayat-menu/restore-single', [HistoryController::class, 'restoreSingle'])->name('history.restoreSingle');
    Route::post('/riwayat-menu/restore-week', [HistoryController::class, 'restoreFull'])->name('history.restoreFull');

    // Halaman Favorit
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorites.index');

    // Action Like/Unlike
    Route::post('/favorit/toggle/{menu_id}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
