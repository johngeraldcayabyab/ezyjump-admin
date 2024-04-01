<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class WalletMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Str::contains($request->host(), config('domain.gateway_dashboard_domain'))) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
