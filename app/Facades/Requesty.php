<?php

namespace App\Facades;

use Illuminate\Support\Str;

class Requesty
{
    public static function isGateway()
    {
        if (trim(request()->host()) === trim(config('domain.gateway_dashboard_domain'))) {
            return true;
        }
        return false;
    }

    public static function isWallet()
    {
        if (trim(request()->host()) === trim(config('domain.wallet_dashboard_domain'))) {
            return true;
        }
        return false;
    }
}
