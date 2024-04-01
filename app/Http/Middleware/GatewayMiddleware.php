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
            info('suffering 1');
            return redirect()->route('wallet.dashboard');
        }
        return $next($request);
    }
}
