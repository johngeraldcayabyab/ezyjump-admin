<?php

use App\Http\Controllers\CallbackReceiverController;
use App\Http\Controllers\Gateway\GatewayMerchantController;
use App\Http\Controllers\Gateway\GatewaySwiftpayCallbackController;
use App\Http\Controllers\Gateway\GatewaySwiftpayOrderController;
use App\Http\Controllers\Gateway\GatewayTenantController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\Wallet\WalletArxPaymentController;
use App\Http\Controllers\Wallet\WalletMagpieDepositController;
use App\Http\Controllers\Wallet\WalletTraxionGcashPaymentController;
use App\Http\Controllers\Wallet\WalletWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('gateway')->group(function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get("swiftpay-orders", [GatewaySwiftpayOrderController::class, 'index'])->name('gateway.swiftpay-orders.index');
        Route::get("swiftpay-orders/{swiftpay_order}", [GatewaySwiftpayOrderController::class, 'show'])->name('gateway.swiftpay-orders.show');
        Route::get("swiftpay-callbacks", [GatewaySwiftpayCallbackController::class, 'index'])->name('gateway.swiftpay-callback.index');
        Route::post('swiftpay/sync', [GatewaySwiftpayOrderController::class, 'sync'])->name('gateway.swiftpay.sync');
        Route::post('swiftpay/retry-callback', [GatewaySwiftpayOrderController::class, 'retryCallback'])->name('gateway.swiftpay.retry-callback');
        Route::post("merchants/toggle", [GatewayMerchantController::class, 'toggle'])->name('gateway.merchants.toggle');
    });
    Route::get('tenant/exposed', [GatewayTenantController::class, 'expose'])->name('gateway.tenant.exposed');
    Route::post('swiftpay/order', [GatewaySwiftpayOrderController::class, 'order'])->name('gateway.swiftpay.order');
});


Route::prefix('wallet')->group(function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get("traxion-gcash-payment", [WalletTraxionGcashPaymentController::class, 'index'])->name('wallet.payments-1.index');
        Route::get("arx-payment", [WalletArxPaymentController::class, 'index'])->name('wallet.payments-2.index');
        Route::get("magpie-deposit", [WalletMagpieDepositController::class, 'index'])->name('wallet.payments-3.index');
        Route::post("magpie-deposit/force-pay", [WalletMagpieDepositController::class, 'forcePay'])->name('wallet.payments-3.force-pay');
        Route::get("webhook", [WalletWebhookController::class, 'index'])->name('wallet.webhooks.index');
        Route::post('webhook/retry', [WalletWebhookController::class, 'retry'])->name('wallet.webhooks.retry');
        Route::post('cashin/sync', [WalletArxPaymentController::class, 'sync'])->name('wallet.payments-2.sync');
    });
});

Route::get('magpie/pass-through', [CallbackReceiverController::class, 'magpie'])->name('magpie.pass-through');
Route::post('magpie/callback', [CallbackReceiverController::class, 'postback'])->name('magpie.callback');
Route::post('magpie/success', [CallbackReceiverController::class, 'success'])->name('magpie.success');
Route::post('magpie/failed', [CallbackReceiverController::class, 'failed'])->name('magpie.failed');
Route::post('magpie/sync', [CallbackReceiverController::class, 'sync'])->name('magpie.sync');
Route::post('callback/receiver', [CallbackReceiverController::class, 'receiver'])->name('callbacks.receiver');
Route::post('telegram/receiver', [TelegramController::class, 'receiver'])->name('telegram.receiver');
