<?php

use App\Http\Controllers\Gateway\GatewayDashboardController;
use App\Http\Controllers\Gateway\GatewayMerchantController;
use App\Http\Controllers\Gateway\GatewayProfileController;
use App\Http\Controllers\Gateway\GatewayTransactionController;
use App\Http\Controllers\Wallet\WalletDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware(['auth', 'verified', 'gateway'])->prefix('gateway')->group(function () {
    Route::get('/dashboard', [GatewayDashboardController::class, 'view'])->name('dashboard');
    Route::get('/profile', [GatewayProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [GatewayProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [GatewayProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/transactions', [GatewayTransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/channel-1', [GatewayTransactionController::class, 'swiftView'])->name('transactions.swiftpay.show');
    Route::get('/transactions/channel-2', [GatewayTransactionController::class, 'swiftQrView'])->name('transactions.swiftpay.qr.show');
    Route::get('/merchants', [GatewayMerchantController::class, 'show'])->name('merchants.show');
});

Route::middleware(['auth:wallet', 'wallet'])->prefix('wallet')->group(function () {
    Route::get('/dashboard', [WalletDashboardController::class, 'view'])->name('wallet.dashboard');
});

require __DIR__ . '/auth.php';
