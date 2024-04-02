<?php

namespace App\Http\Controllers\AuthGateway;

use App\Facades\Authy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GatewayAuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('gateway-auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::GATEWAY_HOME);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Authy::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('gateway.login');
    }
}
