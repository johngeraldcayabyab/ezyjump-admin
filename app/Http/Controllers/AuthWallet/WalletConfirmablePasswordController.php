<?php

namespace App\Http\Controllers\AuthWallet;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WalletConfirmablePasswordController extends Controller
{
    public function show(): View
    {
        return view('wallet-auth.confirm-password');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Authy::validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::WALLET_HOME);
    }
}
