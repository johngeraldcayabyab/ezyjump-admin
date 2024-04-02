<?php

use App\Http\Controllers\AuthGateway\GatewayAuthenticatedSessionController;
use App\Http\Controllers\AuthGateway\GatewayConfirmablePasswordController;
use App\Http\Controllers\AuthGateway\GatewayEmailVerificationNotificationController;
use App\Http\Controllers\AuthGateway\GatewayEmailVerificationPromptController;
use App\Http\Controllers\AuthGateway\GatewayNewPasswordController;
use App\Http\Controllers\AuthGateway\GatewayPasswordController;
use App\Http\Controllers\AuthGateway\GatewayVerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->prefix('gateway')->group(function () {
    Route::get('login', [GatewayAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [GatewayAuthenticatedSessionController::class, 'store']);
    Route::get('reset-password/{token}', [GatewayNewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [GatewayNewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->prefix('gateway')->group(function () {
    Route::get('verify-email', GatewayEmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', GatewayVerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [GatewayEmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', [GatewayConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [GatewayConfirmablePasswordController::class, 'store']);
    Route::put('password', [GatewayPasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [GatewayAuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
