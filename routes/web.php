<?php

use App\Http\Controllers\CashierController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\FruitController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfitLossReportController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');

    Route::get('kelola/user', [UserController::class, 'index'])->name('management.users');
    Route::get('kelola/buah', [FruitController::class, 'index'])->name('management.fruits');
    Route::get('kelola/supplier', [SupplierController::class, 'index'])->name('management.suppliers');
    Route::get('kelola/stok', [StockController::class, 'index'])->name('management.stocks');
    Route::get('kelola/transaksi', [TransactionController::class, 'index'])->name('management.transactions');

    Route::get('kasir', [CashierController::class, 'index'])->name('cashier');

    Route::get('laporan/penjualan', [SalesReportController::class, 'index'])->name('reports.sales');
    Route::get('laporan/laba-rugi', [ProfitLossReportController::class, 'index'])->name('reports.profit-loss');

    Route::post('logout', LogoutController::class)->name('logout');
});
