<?php

use App\Http\Controllers\AuthGateway\GatewayAuthenticatedSessionController;
use App\Http\Controllers\AuthGateway\GatewayConfirmablePasswordController;
use App\Http\Controllers\AuthGateway\GatewayEmailVerificationNotificationController;
use App\Http\Controllers\AuthGateway\GatewayEmailVerificationPromptController;
use App\Http\Controllers\AuthGateway\GatewayNewPasswordController;
use App\Http\Controllers\AuthGateway\GatewayPasswordController;
use App\Http\Controllers\AuthGateway\GatewayVerifyEmailController;
use App\Http\Controllers\AuthWallet\WalletAuthenticatedSessionController;
use App\Http\Controllers\AuthWallet\WalletConfirmablePasswordController;
use App\Http\Controllers\AuthWallet\WalletEmailVerificationNotificationController;
use App\Http\Controllers\AuthWallet\WalletEmailVerificationPromptController;
use App\Http\Controllers\AuthWallet\WalletNewPasswordController;
use App\Http\Controllers\AuthWallet\WalletPasswordController;
use App\Http\Controllers\AuthWallet\WalletVerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest-gateway')->prefix('gateway')->group(function () {
    Route::get('login', [GatewayAuthenticatedSessionController::class, 'create'])->name('gateway.login');
    Route::post('login', [GatewayAuthenticatedSessionController::class, 'store']);
    Route::get('reset-password/{token}', [GatewayNewPasswordController::class, 'create'])->name('gateway.password.reset');
    Route::post('reset-password', [GatewayNewPasswordController::class, 'store'])->name('gateway.password.store');
});

Route::middleware('auth-gateway')->prefix('gateway')->group(function () {
    Route::get('verify-email', GatewayEmailVerificationPromptController::class)->name('gateway.verification.notice');
    Route::get('verify-email/{id}/{hash}', GatewayVerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('gateway.verification.verify');
    Route::post('email/verification-notification', [GatewayEmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('gateway.verification.send');
    Route::get('confirm-password', [GatewayConfirmablePasswordController::class, 'show'])
        ->name('gateway.password.confirm');
    Route::post('confirm-password', [GatewayConfirmablePasswordController::class, 'store']);
    Route::put('password', [GatewayPasswordController::class, 'update'])->name('gateway.password.update');
    Route::post('logout', [GatewayAuthenticatedSessionController::class, 'destroy'])
        ->name('gateway.logout');
});

Route::middleware('guest-wallet')->prefix('wallet')->group(function () {
    Route::get('login', [WalletAuthenticatedSessionController::class, 'create'])->name('wallet.login');
    Route::post('login', [WalletAuthenticatedSessionController::class, 'store']);
    Route::get('reset-password/{token}', [WalletNewPasswordController::class, 'create'])->name('wallet.password.reset');
    Route::post('reset-password', [WalletNewPasswordController::class, 'store'])->name('wallet.password.store');
});


Route::middleware('auth-wallet')->prefix('wallet')->group(function () {
    Route::get('verify-email', WalletEmailVerificationPromptController::class)->name('wallet.verification.notice');
    Route::get('verify-email/{id}/{hash}', WalletVerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('wallet.verification.verify');
    Route::post('email/verification-notification', [WalletEmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('wallet.verification.send');
    Route::get('confirm-password', [WalletConfirmablePasswordController::class, 'show'])
        ->name('wallet.password.confirm');
    Route::post('confirm-password', [WalletConfirmablePasswordController::class, 'store']);
    Route::put('password', [WalletPasswordController::class, 'update'])->name('wallet.password.update');
    Route::post('logout', [WalletAuthenticatedSessionController::class, 'destroy'])
        ->name('wallet.logout');
});
