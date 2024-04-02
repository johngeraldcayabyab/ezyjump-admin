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
        info('wallet redirection');
        $guards = empty($guards) ? [null] : $guards;
        info($guards);
        foreach ($guards as $guard) {
            if (Requesty::isWallet() && Auth::guard($guard)->check()) {
                info('fuck 1');
                return redirect(RouteServiceProvider::WALLET_HOME);
            }
        }
        info(Auth::guard('wallet')->user());
//        info($request->route());
        info('fuck 2');
        return $next($request);
    }
}
