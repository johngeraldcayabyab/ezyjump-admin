<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class GatewayMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Str::contains($request->host(), config('domain.wallet_dashboard_domain'))) {
            return redirect()->route('wallet.dashboard');
        }
        return $next($request);
    }
}
