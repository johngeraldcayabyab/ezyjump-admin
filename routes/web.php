<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Wallet\WalletDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware(['auth', 'verified', 'gateway'])->prefix('gateway')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'view'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/transactions', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/channel-1', [TransactionController::class, 'swiftView'])->name('transactions.swiftpay.show');
    Route::get('/transactions/channel-2', [TransactionController::class, 'swiftQrView'])->name('transactions.swiftpay.qr.show');
    Route::get('/merchants', [MerchantController::class, 'show'])->name('merchants.show');
});

Route::middleware(['auth', 'verified', 'dashboard'])->prefix('wallet')->group(function () {
    Route::get('/dashboard', [WalletDashboardController::class, 'view'])->name('wallet.dashboard');
});

require __DIR__ . '/auth.php';
