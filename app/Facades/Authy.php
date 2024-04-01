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
        if (Requesty::isGateway()) {
            return Auth::guard('web');
        } else if (Requesty::isWallet()) {
            return Auth::guard('wallet');
        }
        return Auth::guard('web');
    }
}
