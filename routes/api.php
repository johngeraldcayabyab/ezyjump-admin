<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\SwiftpayOrderController;
use App\Http\Controllers\SwiftpayQueryOrderController;
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
    Route::get("swiftpay_query_orders/statistics", [SwiftpayQueryOrderController::class, 'statistics'])->name('swiftpay_query_orders.statistics');

    Route::post("merchants/toggle", [MerchantController::class, 'toggle'])->name('merchants.toggle');
});

Route::post('swiftpay/order', [SwiftpayOrderController::class, 'order'])->name('swiftpay.order');
