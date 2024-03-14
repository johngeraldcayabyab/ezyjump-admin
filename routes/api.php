<?php

use App\Http\Controllers\GcashPaymentController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\PayboritPaymentHistoryController;
use App\Http\Controllers\SwiftpayCallbackController;
use App\Http\Controllers\SwiftpayOrderController;
use App\Http\Controllers\SwiftpayQrOrderHistoryController;
use App\Http\Controllers\SwiftpayQueryOrderController;
use App\Http\Controllers\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('web')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['web']], function () {
    Route::get("swiftpay_query_orders", [SwiftpayQueryOrderController::class, 'index'])->name('swiftpay_query_orders.index');
    Route::get("swiftpay_query_orders/{swiftpay_query_order}", [SwiftpayQueryOrderController::class, 'show'])->name('swiftpay_query_orders.show');
    Route::get("swiftpay_query_orders/statistics", [SwiftpayQueryOrderController::class, 'statistics'])->name('swiftpay_query_orders.statistics');
    Route::get("swiftpay-callbacks", [SwiftpayCallbackController::class, 'index'])->name('swiftpay-callback.index');
    Route::post('swiftpay/sync', [SwiftpayOrderController::class, 'sync'])->name('swiftpay.sync');
    Route::post('swiftpay/retry-callback', [SwiftpayOrderController::class, 'retryCallback'])->name('swiftpay.retry-callback');

    Route::get("gcash_payments", [GcashPaymentController::class, 'index'])->name('gcash_payments.index');
    Route::get("payborit-payment-history", [PayboritPaymentHistoryController::class, 'index'])->name('payborit-payment-history.index');

    Route::get("swiftpay_qr_query_orders", [SwiftpayQrOrderHistoryController::class, 'index'])->name('swiftpay_qr_query_orders.index');

    Route::post("merchants/toggle", [MerchantController::class, 'toggle'])->name('merchants.toggle');
});

Route::get('tenant/exposed', [TenantController::class, 'expose'])->name('tenant.exposed');
Route::post('swiftpay/order', [SwiftpayOrderController::class, 'order'])->name('swiftpay.order');
