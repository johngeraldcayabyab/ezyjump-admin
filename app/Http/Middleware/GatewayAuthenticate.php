<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class GatewayAuthenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        $shing = $request->expectsJson();
        info('--start--');
        info($shing);
        info('--end--');
        return $shing ? null : route('gateway.login');
    }
}
