<?php

use App\Http\Controllers\SwiftpayOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("swiftpay_orders", [SwiftpayOrderController::class, 'index'])->name('swiftpay_orders.index');
