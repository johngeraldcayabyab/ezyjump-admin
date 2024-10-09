<?php

namespace App\Http\Middleware;

use App\Facades\Requesty;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WalletMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        info([request()->host(), config('domain.wallet_dashboard_domain')]);
        if (Requesty::isGateway()) {
            return redirect()->route('gateway.dashboard');
        }
        return $next($request);
    }
}
