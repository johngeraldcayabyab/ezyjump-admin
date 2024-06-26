<?php

use App\Facades\Requesty;
use App\Http\Controllers\Gateway\GatewayDashboardController;
use App\Http\Controllers\Gateway\GatewayMerchantController;
use App\Http\Controllers\Gateway\GatewayProfileController;
use App\Http\Controllers\Gateway\GatewayTransactionController;
use App\Http\Controllers\Wallet\WalletArxPaymentController;
use App\Http\Controllers\Wallet\WalletDashboardController;
use App\Http\Controllers\Wallet\WalletMagpieDepositController;
use App\Http\Controllers\Wallet\WalletTraxionGcashPaymentController;
use App\Http\Controllers\Wallet\WalletWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Requesty::isWallet()) {
        return redirect(route('wallet.login'));
    }
    return redirect(route('gateway.login'));
});

Route::get('/login', function () {
    if (Requesty::isWallet()) {
        return redirect(route('wallet.login'));
    }
    return redirect(route('gateway.login'));
})->name('login');

Route::middleware(['auth-gateway', 'gateway'])->prefix('gateway')->group(function () {
    Route::get('/dashboard', [GatewayDashboardController::class, 'view'])->name('gateway.dashboard');
    Route::get('/profile', [GatewayProfileController::class, 'edit'])->name('gateway.profile.edit');
    Route::patch('/profile', [GatewayProfileController::class, 'update'])->name('gateway.profile.update');
    Route::delete('/profile', [GatewayProfileController::class, 'destroy'])->name('gateway.profile.destroy');
    Route::get('/transactions', [GatewayTransactionController::class, 'swiftView'])->name('gateway.transactions.show');
    Route::get('/transactions/channel-1', [GatewayTransactionController::class, 'swiftView'])->name('gateway.transactions.swiftpay.show');
    Route::get('/merchants', [GatewayMerchantController::class, 'show'])->name('gateway.merchants.show');
});

Route::middleware(['auth-wallet:wallet', 'wallet'])->prefix('wallet')->group(function () {
    Route::get('/dashboard', [WalletDashboardController::class, 'view'])->name('wallet.dashboard');
    Route::get('/payments/channel-1', [WalletTraxionGcashPaymentController::class, 'view'])->name('wallet.payments-1.view');
    Route::get('/payments/channel-2', [WalletArxPaymentController::class, 'view'])->name('wallet.payments-2.view');
    Route::get('/payments/channel-3', [WalletMagpieDepositController::class, 'view'])->name('wallet.payments-3.view');
    Route::get('/webhooks', [WalletWebhookController::class, 'view'])->name('wallet.webhooks.view');
});

require __DIR__ . '/auth.php';
