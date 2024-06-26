<?php

namespace App\Http\Controllers\AuthWallet;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\WalletLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletAuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('wallet-auth.login');
    }

    public function store(WalletLoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect(RouteServiceProvider::WALLET_HOME);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Authy::logout();
        session()->forget('user_metadata');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('wallet.login');
    }
}
