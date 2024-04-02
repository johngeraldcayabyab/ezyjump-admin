<?php

use App\Facades\Requesty;
use App\Http\Controllers\Gateway\GatewayDashboardController;
use App\Http\Controllers\Gateway\GatewayMerchantController;
use App\Http\Controllers\Gateway\GatewayProfileController;
use App\Http\Controllers\Gateway\GatewayTransactionController;
use App\Http\Controllers\Wallet\WalletDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Requesty::isWallet()) {
        return redirect(route('wallet.login'));
    }
    return redirect(route('gateway.login'));
});

Route::middleware(['auth-gateway', 'verified'])->prefix('gateway')->group(function () {
    Route::get('/dashboard', [GatewayDashboardController::class, 'view'])->name('gateway.dashboard');
    Route::get('/profile', [GatewayProfileController::class, 'edit'])->name('gateway.profile.edit');
    Route::patch('/profile', [GatewayProfileController::class, 'update'])->name('gateway.profile.update');
    Route::delete('/profile', [GatewayProfileController::class, 'destroy'])->name('gateway.profile.destroy');
    Route::get('/transactions', [GatewayTransactionController::class, 'show'])->name('gateway.transactions.show');
    Route::get('/transactions/channel-1', [GatewayTransactionController::class, 'swiftView'])->name('gateway.transactions.swiftpay.show');
    Route::get('/transactions/channel-2', [GatewayTransactionController::class, 'swiftQrView'])->name('gateway.transactions.swiftpay.qr.show');
    Route::get('/merchants', [GatewayMerchantController::class, 'show'])->name('gateway.merchants.show');
});

Route::middleware(['auth-wallet:wallet', 'wallet'])->prefix('wallet')->group(function () {
    Route::get('/dashboard', [WalletDashboardController::class, 'view'])->name('wallet.dashboard');
});

require __DIR__ . '/auth.php';
