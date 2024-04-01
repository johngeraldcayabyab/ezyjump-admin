<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Authy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'authy';
    }

    protected static function resolveFacadeInstance($name)
    {
        if (Str::contains(request()->host(), config('domain.gateway_dashboard_domain'))) {
            return Auth::guard('web');
        } else if (Str::contains(request()->host(), config('domain.wallet_dashboard_domain'))) {
            return Auth::guard('wallet');
        }
        return Auth::guard('web');
    }
}
