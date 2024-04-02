<?php

namespace App\Http\Middleware;

use App\Facades\Authy;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GatewayRedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        info('gateway redirection');
        $guards = empty($guards) ? [null] : $guards;
        info($guards);
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                info('g fuck 1');
                return redirect(RouteServiceProvider::GATEWAY_HOME);
            }
        }
        info(Auth::guard('web')->user());
//        info($request->route());
        info('g fuck 2');
        return $next($request);
    }
}
