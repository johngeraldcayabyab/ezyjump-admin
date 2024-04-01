<?php

namespace App\Facades;

use Illuminate\Support\Str;

class Requesty
{
    public static function isGateway()
    {
        if (Str::contains(request()->host(), config('domain.gateway_dashboard_domain'))) {
            return true;
        }
        return false;
    }

    public static function isWallet()
    {
        if (Str::contains(request()->host(), config('domain.wallet_dashboard_domain'))) {
            return true;
        }
        return false;
    }
}
