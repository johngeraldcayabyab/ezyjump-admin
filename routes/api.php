<?php

use App\Http\Controllers\CallbackReceiverController;
use App\Http\Controllers\Gateway\GatewayMerchantController;
use App\Http\Controllers\Gateway\GatewaySwiftpayCallbackController;
use App\Http\Controllers\Gateway\GatewaySwiftpayOrderController;
use App\Http\Controllers\Gateway\GatewaySwiftpayQrOrderHistoryController;
use App\Http\Controllers\Gateway\GatewayTenantController;
use App\Http\Controllers\Wallet\WalletArxPaymentController;
use App\Http\Controllers\Wallet\WalletTraxionGcashPaymentController;
use App\Http\Controllers\Wallet\WalletWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('gateway')->group(function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get("swiftpay_query_orders", [GatewaySwiftpayOrderController::class, 'index'])->name('gateway.swiftpay_query_orders.index');
        Route::get("swiftpay_query_orders/{swiftpay_order}", [GatewaySwiftpayOrderController::class, 'show'])->name('gateway.swiftpay_query_orders.show');
        Route::get("swiftpay-callbacks", [GatewaySwiftpayCallbackController::class, 'index'])->name('gateway.swiftpay-callback.index');
        Route::post('swiftpay/sync', [GatewaySwiftpayOrderController::class, 'sync'])->name('gateway.swiftpay.sync');
        Route::post('swiftpay/retry-callback', [GatewaySwiftpayOrderController::class, 'retryCallback'])->name('gateway.swiftpay.retry-callback');
        Route::get("swiftpay_qr_query_orders", [GatewaySwiftpayQrOrderHistoryController::class, 'index'])->name('gateway.swiftpay_qr_query_orders.index');
        Route::post("merchants/toggle", [GatewayMerchantController::class, 'toggle'])->name('gateway.merchants.toggle');
    });
    Route::get('tenant/exposed', [GatewayTenantController::class, 'expose'])->name('gateway.tenant.exposed');
    Route::post('swiftpay/order', [GatewaySwiftpayOrderController::class, 'order'])->name('gateway.swiftpay.order');
});


Route::prefix('wallet')->group(function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get("traxion-gcash-payment", [WalletTraxionGcashPaymentController::class, 'index'])->name('wallet.payments-1.index');
        Route::get("arx-payment", [WalletArxPaymentController::class, 'index'])->name('wallet.payments-2.index');
        Route::get("webhook", [WalletWebhookController::class, 'index'])->name('wallet.webhooks.index');
        Route::post('webhook/retry', [WalletWebhookController::class, 'retry'])->name('wallet.webhooks.retry');
        Route::post('cashin/sync', [WalletArxPaymentController::class, 'sync'])->name('wallet.payments-2.sync');
    });
});

Route::post('callback/receiver', [CallbackReceiverController::class, 'receiver'])->name('callbacks.receiver');
