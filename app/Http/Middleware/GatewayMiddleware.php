<?php

namespace App\Http\Middleware;

use App\Facades\Requesty;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GatewayMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Requesty::isWallet()) {
            info([request()->host(), config('domain.gateway_dashboard_domain')]);
            return redirect()->route('wallet.dashboard');
        }
        info('did not pass 1');
        return $next($request);
    }
}
