<?php

use App\Http\Controllers\Gateway\GatewayMerchantController;
use App\Http\Controllers\Gateway\GatewaySwiftpayCallbackController;
use App\Http\Controllers\Gateway\GatewaySwiftpayOrderController;
use App\Http\Controllers\Gateway\GatewaySwiftpayQrOrderHistoryController;
use App\Http\Controllers\Gateway\GatewaySwiftpayQueryOrderController;
use App\Http\Controllers\Gateway\GatewayTenantController;
use Illuminate\Support\Facades\Route;

Route::prefix('gateway')->group(function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get("swiftpay_query_orders", [GatewaySwiftpayQueryOrderController::class, 'index'])->name('swiftpay_query_orders.index');
        Route::get("swiftpay_query_orders/{swiftpay_query_order}", [GatewaySwiftpayQueryOrderController::class, 'show'])->name('swiftpay_query_orders.show');
        Route::get("swiftpay_query_orders/statistics", [GatewaySwiftpayQueryOrderController::class, 'statistics'])->name('swiftpay_query_orders.statistics');
        Route::get("swiftpay-callbacks", [GatewaySwiftpayCallbackController::class, 'index'])->name('swiftpay-callback.index');
        Route::post('swiftpay/sync', [GatewaySwiftpayOrderController::class, 'sync'])->name('swiftpay.sync');
        Route::post('swiftpay/retry-callback', [GatewaySwiftpayOrderController::class, 'retryCallback'])->name('swiftpay.retry-callback');
        Route::get("swiftpay_qr_query_orders", [GatewaySwiftpayQrOrderHistoryController::class, 'index'])->name('swiftpay_qr_query_orders.index');
        Route::post("merchants/toggle", [GatewayMerchantController::class, 'toggle'])->name('merchants.toggle');
    });
    Route::get('tenant/exposed', [GatewayTenantController::class, 'expose'])->name('tenant.exposed');
    Route::post('swiftpay/order', [GatewaySwiftpayOrderController::class, 'order'])->name('swiftpay.order');
});
