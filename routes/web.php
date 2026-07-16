<?php

use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ManagementPageController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('dashboard');

Route::prefix('kelola')->name('management.')->controller(ManagementPageController::class)->group(function () {
    Route::get('user', 'users')->name('users');
    Route::get('buah', 'fruits')->name('fruits');
    Route::get('supplier', 'suppliers')->name('suppliers');
    Route::get('stok', 'stocks')->name('stocks');
    Route::get('transaksi', 'transactions')->name('transactions');
});

Route::get('kasir', [ManagementPageController::class, 'cashier'])->name('cashier');

Route::prefix('laporan')->name('reports.')->controller(ManagementPageController::class)->group(function () {
    Route::get('penjualan', 'salesReport')->name('sales');
    Route::get('laba-rugi', 'profitLossReport')->name('profit-loss');
});

Route::post('logout', LogoutController::class)->name('logout');
