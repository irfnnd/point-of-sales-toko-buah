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

    Route::get('data/user', [UserController::class, 'index'])->name('data.users');
    Route::get('data/buah', [FruitController::class, 'index'])->name('data.fruits');
    Route::post('data/buah', [FruitController::class, 'store'])->name('data.fruits.store');
    Route::put('data/buah/{fruit}', [FruitController::class, 'update'])->name('data.fruits.update');
    Route::delete('data/buah/{fruit}', [FruitController::class, 'destroy'])->name('data.fruits.destroy');
    Route::get('data/supplier', [SupplierController::class, 'index'])->name('data.suppliers');
    Route::get('data/stok', [StockController::class, 'index'])->name('data.stocks');
    Route::get('data/transaksi', [TransactionController::class, 'index'])->name('data.transactions');

    Route::get('kasir', [CashierController::class, 'index'])->name('cashier');

    Route::get('laporan/penjualan', [SalesReportController::class, 'index'])->name('reports.sales');
    Route::get('laporan/laba-rugi', [ProfitLossReportController::class, 'index'])->name('reports.profit-loss');

    Route::post('logout', LogoutController::class)->name('logout');
});
