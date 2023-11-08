<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/transactions', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/channel-1', [TransactionController::class, 'swiftView'])->name('transactions.swiftpay.show');
    Route::get('/transactions/channel-2', [TransactionController::class, 'gcashView'])->name('transactions.gcash.show');
    Route::get('/merchants', [MerchantController::class, 'show'])->name('merchants.show');
});

require __DIR__ . '/auth.php';
