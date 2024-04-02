<?php

namespace App\Http\Middleware;

use App\Facades\Requesty;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WalletRedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;;
        foreach ($guards as $guard) {
            if (Requesty::isWallet() && Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::WALLET_HOME);
            }
        }
        return $next($request);
    }
}
