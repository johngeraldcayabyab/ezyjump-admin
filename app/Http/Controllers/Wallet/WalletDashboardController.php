<?php

namespace App\Http\Controllers\Wallet;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WalletDashboardController
{
    public function view()
    {
        Log::channel('wallet')->info('WAS I FUCKING REDIRECTED HERE');
        return view('wallet-dashboard');
    }
}
