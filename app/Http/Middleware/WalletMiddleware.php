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
        if (Requesty::isGateway()) {
            info([request()->host(), config('domain.wallet_dashboard_domain')]);
            return redirect()->route('gateway.dashboard');
        }
        info('did not pass 2');
        return $next($request);
    }
}
