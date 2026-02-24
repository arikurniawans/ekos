<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\PenghuniController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('login');
});

// LOGIN ROUTE (TANPA AUTH)

Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// ================= ROUTE YANG HARUS LOGIN =====================================================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // ================= KAMAR =================
    Route::prefix('kamar')->name('kamar.')->controller(KamarController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // ================= PENGHUNI =================
    Route::prefix('penghuni')->name('penghuni.')->controller(PenghuniController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // ================= PEMBAYARAN =================
    Route::prefix('pembayaran')->name('pembayaran.')->controller(PembayaranController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add/{penghuni}', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/detail/{penghuni}', 'detail')->name('detail');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::put('/batal/{penghuni}', 'batal')->name('batal');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });

    // ================= LAPORAN =================
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export-pdf', [LaporanController::class, 'exportPdf'])->name('export.pdf');
    });

});
